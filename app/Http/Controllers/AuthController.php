<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        ]);

        // 3. Auto-login after registration
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful!');
    }
}
