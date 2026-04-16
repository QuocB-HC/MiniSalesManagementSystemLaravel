
@extends('layouts.user', ['hideHeaderFooter' => true])

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
@endpush

@section('content')
    <div class="login-container">
        <div class="login-box">
            <h2>Welcome Back</h2>
            <p>Please enter your details to sign in</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    {{-- @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror --}}
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password">
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href={{ route('register') }}>Create one</a></p>
            </div>

            <div class="login-footer">
                <p>Forget password? <a href={{ route('forget.password') }}>Reset password</a></p>
            </div>
        </div>
    </div>
@endsection
