@extends('layouts.user', ['hideHeaderFooter' => true])

@section('title', 'Forget Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/forget-password.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <div class="forgot-password-card">
            <h1 class="title">Forgot Password</h1>

            <div class="stepper">
                <div class="step active" id="st-1">
                    <div class="step-number">1</div>
                    <div class="step-label">Account</div>
                </div>
                <div class="step-line" id="step-line-1"></div>
                <div class="step" id="st-2">
                    <div class="step-number">2</div>
                    <div class="step-label">Security</div>
                </div>
                <div class="step-line" id="step-line-2"></div>
                <div class="step" id="st-3">
                    <div class="step-number">3</div>
                    <div class="step-label">Reset</div>
                </div>
            </div>

            <div id="step-1" class="step-content">
                <p class="instruction">Enter your email address to recover your account</p>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" id="email" placeholder="example@gmail.com">
                </div>
                <button type="submit" class="btn-submit active" onclick="handleStep1(event)">Continue</button>
            </div>

            <div id="step-2" class="step-content" style="display: none;">
                <p class="instruction">A 6-digit code has been sent to your email</p>
                <div class="otp-container">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric">
                    @endfor
                </div>
                <input type="hidden" name="verify_email_code" id="final_otp">
                <button type="submit" class="btn-submit active" onclick="handleStep2(event)">Verify Code</button>
            </div>

            <div id="step-3" class="step-content" style="display: none;">
                <p class="instruction">Set a strong new password for your account</p>
                <div class="input-group">
                    <label>New Password</label>
                    <input type="password" id="new_password" placeholder="Min. 8 characters">
                    <span class="toggle-password" onclick="togglePassword('new_password', this)">👁️</span>
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" id="confirm_password" placeholder="Repeat password">
                    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁️</span>
                </div>
                <button type="submit" class="btn-submit active" onclick="handleStep3(event)">Update Password</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Handles UI transitions between reset steps
         * @param {number} step - The target step number (1, 2, or 3)
         */
        function goToStep(step) {
            // 1. Hide all step contents
            document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');

            // 2. Show the target step content
            document.getElementById('step-' + step).style.display = 'block';

            // 3. Update Stepper Circle indicators
            document.querySelectorAll('.step').forEach((el, idx) => {
                if (idx + 1 <= step) {
                    el.classList.add('active');
                }
            });

            // 4. Update Stepper Connecting Lines
            const lines = document.querySelectorAll('.step-line');
            lines.forEach((line, idx) => {
                if (idx + 1 < step) {
                    line.classList.add('active');
                }
            });
        }

        /**
         * STEP 1: Request OTP via Email
         */
        function handleStep1(event) {
            const btn = event.currentTarget;
            let email = document.getElementById('email').value;

            if (!email) {
                if (typeof showToast === "function") {
                    showToast("warning", "Please enter your email address.");
                } else {
                    alert("Please enter your email address.");
                }

                return;
            }

            // UI State: Loading
            btn.disabled = true;
            const originalText = btn.innerText;
            btn.innerText = 'Sending...';

            fetch("{{ route('password.sendOtp') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showToast === "function") {
                            showToast("success", "Verification code sent!");
                        } else {
                            alert("Verification code sent!");
                        }

                        // Count down timer
                        let seconds = 60;
                        btn.innerText = `Wait ${seconds}s`;

                        let timer = setInterval(function() {
                            seconds--;
                            btn.innerText = `Wait ${seconds}s`;

                            if (seconds <= 0) {
                                clearInterval(timer);
                                btn.disabled = false; // Able to click again
                                btn.innerText = 'Resend code';
                            }
                        }, 1000);

                        goToStep(2); // Proceed to OTP verification
                    } else {
                        if (typeof showToast === "function") {
                            showToast("warning", data.message || "Error sending code!");
                        } else {
                            alert(data.message || "Error sending code!");
                        }

                        btn.disabled = false;
                        btn.innerText = 'Send code';
                    }
                })
                .catch(error => {
                    if (typeof showToast === "function") {
                        showToast("error", error.message);
                    } else {
                        alert(error.message);
                    }

                    btn.disabled = false;
                    btn.innerText = 'Send code';
                });
        }

        // OTP Input
        const inputs = document.querySelectorAll('.otp-input');
        const finalInput = document.getElementById('final_otp');

        inputs.forEach((input, index) => {
            // 1. Enter number
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 0 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateFinalCode();
            });

            // 2. Enter Backspace to delete
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // 3. Handle situations where the user pastes entire codes
            input.addEventListener('paste', (e) => {
                // Block default paste event
                e.preventDefault();

                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);

                if (pastedData.length > 0) {
                    pastedData.split('').forEach((char, i) => {
                        if (inputs[index + i]) {
                            inputs[index + i].value = char;
                        }
                    });

                    const lastInputIndex = Math.min(index + pastedData.length - 1, inputs.length - 1);
                    inputs[lastInputIndex].focus();

                    updateFinalCode();
                }
            });
        });

        function updateFinalCode() {
            let code = "";
            inputs.forEach(input => code += input.value);
            finalInput.value = code; // Assign code to a hidden input to submit the form
        }

        /**
         * STEP 2: Verify the 6-digit OTP
         */
        function handleStep2(event) {
            const btn = event.currentTarget;

            // Get OTP from inputs
            let otp = "";
            document.querySelectorAll('.otp-input').forEach(input => otp += input.value);

            if (otp.length < 6) {
                if (typeof showToast === "function") {
                    showToast("warning", "Please enter the full 6-digit code.");
                } else {
                    alert("Please enter the full 6-digit code.");
                }

                return;
            }

            // UI State: Loading
            btn.disabled = true;
            const originalText = btn.innerText;
            btn.innerText = 'Verifying...';

            fetch("{{ route('password.verifyOtp') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        otp: otp,
                        email: document.getElementById('email').value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showToast === "function") {
                            showToast("success", "Verify OTP successful!");
                        } else {
                            alert("Verify OTP successful!");
                        }

                        goToStep(3); // Proceed to password reset
                    } else {
                        if (typeof showToast === "function") {
                            showToast("warning", data.message || "Invalid or expired OTP.");
                        } else {
                            alert(data.message || "Invalid or expired OTP.");
                        }

                        btn.disabled = false;
                        btn.innerText = originalText;
                    }
                })
                .catch(error => {
                    if (typeof showToast === "function") {
                        showToast("error", error.message || "Connection lost. Please try again.");
                    } else {
                        alert(error.message || "Connection lost. Please try again.");
                    }

                    btn.disabled = false;
                    btn.innerText = originalText;
                });
        }

        // Toggle Password Visibility
        function togglePassword(inputId, iconElement) {
            const passwordInput = document.getElementById(inputId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                iconElement.innerText = "🙈";
            } else {
                passwordInput.type = "password";
                iconElement.innerText = "👁️";
            }
        }

        // Reset Stepper
        function clearStepper() {
            // Reset step circle (steps)
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));

            // Reset step line (lines)
            document.querySelectorAll('.step-line').forEach(el => el.classList.remove('active'));

            // Reset step 1 to the default active setting
            document.getElementById('st-1').classList.add('active');
        }

        /**
         * STEP 3: Submit New Password
         */
        function handleStep3(event) {
            const password = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;
            const btn = event.currentTarget;
            const errorMsg = document.getElementById('error-3');

            // Client-side validation
            if (password.length < 8) {
                if (typeof showToast === "function") {
                    showToast("warning", "Password must be at least 8 characters long.");
                } else {
                    alert("Password must be at least 8 characters long.");
                }

                return;
            }
            if (password !== confirm) {
                if (typeof showToast === "function") {
                    showToast("warning", "Passwords do not match.");
                } else {
                    alert("Passwords do not match.");
                }

                return;
            }

            // UI State: Loading
            btn.disabled = true;
            const originalText = btn.innerText;
            btn.innerText = 'Updating...';

            fetch("{{ route('password.update') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        password: password,
                        password_confirmation: confirm,
                        email: document.getElementById('email').value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showToast === "function") {
                            showToast("success", "Your password has been updated.");
                        } else {
                            alert("Your password has been updated.");
                        }

                        window.location.href = "{{ route('login') }}";
                    } else {
                        if (typeof showToast === "function") {
                            showToast("warning", data.message || 'Update failed. Please try again.');
                        } else {
                            alert(data.message || 'Update failed. Please try again.');
                        }

                        btn.disabled = false;
                        btn.innerText = originalText;

                        clearStepper();
                        goToStep(1);
                    }
                })
                .catch(error => {
                    if (typeof showToast === "function") {
                        showToast("error", error.message || 'System error. Contact support if this persists.');
                    } else {
                        alert(error.message || 'System error. Contact support if this persists.');
                    }

                    btn.disabled = false;
                    btn.innerText = originalText;

                    clearStepper();
                    goToStep(1);
                });
        }
    </script>
@endpush
