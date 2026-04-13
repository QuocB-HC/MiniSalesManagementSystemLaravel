<?php

namespace App\Http\Controllers;

use App\Mail\VerifyCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        // 1. Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Check credentials and attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security: regenerate session to prevent fixation

            if (Auth::user()->is_banned) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account has been banned. Please contact support.',
                ])->onlyInput('email');
            }

            // Redirect to intended page or home with success message
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
            } else {
                return redirect()->intended('/')->with('success', 'Login successful!');
            }
        }

        // 3. If credentials are incorrect
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

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

            return back()->withErrors([
                'verify_email_code' => 'The verification code has expired. Please resend a new code!',
            ])->withInput();
        }

        if ($request->verify_email_code != $sessionCode || $request->email != $sessionEmail) {
            return back()->withErrors([
                'verify_email_code' => 'The verification code is incorrect or has expired.',
            ])->withInput();
        }

        session()->forget(['verify_code']);

        return redirect()->route('register.complete')->with('success', 'Verification successful!');
    }

    public function register(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Need input password_confirmation
        ]);

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
}
