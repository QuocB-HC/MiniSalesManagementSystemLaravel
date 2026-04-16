@extends('layouts.user', ['hideHeaderFooter' => true])

@section('title', 'Register')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/register.css') }}">
@endpush

@section('content')
    <div class="register-container">
        <div class="register-box">
            <h2>Create Account</h2>
            <p>Join our store today</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ session('verify_email') }}">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    {{-- @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror --}}
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password">
                    {{-- @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror --}}
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation">
                </div>

                <button type="submit" class="btn-register">Complete registration</button>
            </form>
        </div>
    </div>
@endsection
