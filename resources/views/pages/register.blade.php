<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/pages/register.css') }}">
</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <h2>Create Account</h2>
            <p>Join our store today</p>

            <form action="{{ route('verify.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-with-button">
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        <button type="button" class="send-code-btn" id="btnSendCode">Send code</button>
                    </div>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Code</label>
                    <input type="text" name="verify_email_code" required>
                    @error('verify_email_code')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-register">Sign Up</button>
            </form>

            <div class="register-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnSendCode').addEventListener('click', function() {
            let btn = this;
            let email = document.querySelector('input[name="email"]').value;

            if (!email) {
                alert('Please enter email first!');
                return;
            }

            // 1. Disable button after clicked
            btn.disabled = true;
            btn.innerText = 'Sending...';

            fetch("{{ route('send.code') }}", {
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
                        alert('Verification code sent!');

                        // 2. Count down timer
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
                    } else {
                        alert('Error sending code!');
                        btn.disabled = false;
                        btn.innerText = 'Send code';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.disabled = false;
                    btn.innerText = 'Send code';
                });
        });
    </script>
</body>

</html>
