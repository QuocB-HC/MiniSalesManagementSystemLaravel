<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Mail\VerifyCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required',
        ], [
            'email.required' => 'Please enter your email.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'This email address is not registered.',
            'password.required' => 'Please enter your password.',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        // 2. Check credentials and attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security: regenerate session to prevent fixation

            if (Auth::user()->is_banned) {
                Auth::logout();

                return back()->with('error', 'Your account has been banned. Please contact support.')->onlyInput('email');
            }

            // Redirect to intended page or home with success message
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
            } else {
                return redirect()->intended('/')->with('success', 'Login successful!');
            }
        }

        // 3. If credentials are incorrect
        return back()->with('error', 'The password is incorrect.')->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout successful!');
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
        ], [
            'email.unique' => 'This email address is already registered.',
            'email.required' => 'Please enter your email.',
            'email.email' => 'Invalid email format.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        // Create random 6 numbers code
        $code = rand(100000, 999999);

        // Save into Session (lasts for 5 minutes)
        session([
            'verify_code' => $code,
            'verify_email' => $request->email,
            'verify_code_expires_at' => now()->addMinutes(5),
        ]);

        // Send verify code mail
        Mail::to($request->email)->send(new VerifyCodeMail($code));

        return response()->json(['success' => true, 'message' => 'Code sent successfully!']);
    }

    public function showCompleteRegister()
    {
        return view('pages.register-complete');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'verify_email_code' => 'required',
        ]);

        $sessionCode = session('verify_code');
        $sessionEmail = session('verify_email');
        $expiresAt = session('verify_code_expires_at');

        if (! $expiresAt || now()->gt($expiresAt)) {
            session()->forget(['verify_code', 'verify_email', 'verify_code_expires_at']);

            return back()->with('error', 'The verification code has expired. Please resend a new code!')->onlyInput('email');
        }

        if ($request->verify_email_code != $sessionCode || $request->email != $sessionEmail) {
            return back()->withErrors('error', 'The verification code or email is incorrect.')->onlyInput('email');
        }

        session()->forget(['verify_code']);

        return redirect()->route('register.complete')->with('success', 'Verification successful!');
    }

    public function register(Request $request)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required' => 'Please enter your name.',
            'name.max' => 'Name must be less than 255 characters.',
            'email.required' => 'Please enter your email.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'This email address is not registered.',
            'password.required' => 'Please enter your password.',
            'password_confirmation' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->onlyInput('name');
        }

        // 2. Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt password
            'email_verified_at' => now(),
        ]);

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // 3. Auto-login after registration
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful!');
    }

    public function showForgetPassword()
    {
        return view('pages.forget-password');
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Please enter your email.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'This email address is not registered.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        // Create random 6 numbers code
        $code = rand(100000, 999999);

        // Save into Session (lasts for 5 minutes)
        session([
            'verify_code' => $code,
            'verify_email' => $request->email,
            'verify_code_expires_at' => now()->addMinutes(5),
        ]);

        // Send verify code mail
        Mail::to($request->email)->send(new ResetPasswordMail($code));

        return response()->json(['success' => true, 'message' => 'Code sent successfully!']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'otp' => 'required',
        ]);

        $sessionCode = session('verify_code');
        $sessionEmail = session('verify_email');
        $expiresAt = session('verify_code_expires_at');

        if (! $expiresAt || now()->gt($expiresAt)) {
            session()->forget(['verify_code', 'verify_email', 'verify_code_expires_at']);

            return response()->json([
                'success' => false,
                'message' => 'The verification code has expired. Please request a new one.',
            ], 400);
        }

        if ($request->otp != $sessionCode || $request->email != $sessionEmail) {
            return response()->json([
                'success' => false,
                'message' => 'The verification code is incorrect.',
            ], 400);
        }

        session()->forget(['verify_code', 'verify_code_expires_at']);
        session(['otp_verified' => true]);

        return response()->json(['success' => true, 'message' => 'Verification successful!']);
    }

    public function updatePassword(Request $request)
    {
        $email = session('verify_email');
        $otpVerified = session('otp_verified');

        if (! $otpVerified || ! $email) {
            return response()->json([
                'success' => false,
                'message' => 'The session has expired or has not been validated. Please try again from the beginning.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed', // 'confirmed' require password_confirmation
        ], [
            'password.required' => 'Please enter new password',
            'password.min' => 'The password must have at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            session()->forget(['otp_verified', 'verify_email']);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!',
                'redirect_to' => route('login'),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }
}
