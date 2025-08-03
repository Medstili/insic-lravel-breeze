@extends('layouts.guest')

@section('content')
<div class="form-container">
    <!-- Logo and Header -->
    <div class="brand">
        <div class="brand-logo">
            <i class="fas fa-heartbeat"></i>
        </div>
        <h1 class="brand-title">Welcome to Insic</h1>
        <p class="brand-subtitle">Sign in to your healthcare management account</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Field -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope text-primary-600 mr-2"></i>
                Email Address
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   class="form-input @error('email') error @enderror"
                   placeholder="Enter your email address">
            
            @error('email')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock text-primary-600 mr-2"></i>
                Password
            </label>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       class="form-input pr-10 @error('password') error @enderror"
                       placeholder="Enter your password">
                <button type="button" 
                        onclick="togglePassword()" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i id="password-toggle" class="fas fa-eye"></i>
                </button>
            </div>
            
            @error('password')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember"
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link text-sm">
                    Forgot your password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </div>

    </form>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.classList.remove('fa-eye');
            passwordToggle.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordToggle.classList.remove('fa-eye-slash');
            passwordToggle.classList.add('fa-eye');
        }
    }

    function fillDemoCredentials(type) {
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    
    if (type === 'admin') {
        emailField.value = 'admin@insic.com';
        passwordField.value = 'password';
    } else if (type === 'coach') {
        emailField.value = 'coach@insic.com';
        passwordField.value = 'password';
    }
    
    // Add visual feedback
    emailField.classList.add('success');
    passwordField.classList.add('success');
    
    setTimeout(() => {
        emailField.classList.remove('success');
        passwordField.classList.remove('success');
    }, 2000);
}
</script>
@endsection

