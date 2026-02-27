@extends('layouts.app')

@section('title', 'Login — SoulMates Inc.')

@section('head')
<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .login-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
        display: flex;
        min-height: 600px;
    }
    .login-visual {
        flex: 1;
        background: linear-gradient(45deg, #C8A96E, #A8893E);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .login-visual::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    .login-form {
        flex: 1;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .brand-title {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        color: #0D0D0D;
    }
    .brand-subtitle {
        color: #6B6560;
        margin-bottom: 2rem;
    }
    .form-control {
        border: 2px solid #f0f0f0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #C8A96E;
        box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.1);
    }
    .btn-login {
        background: linear-gradient(135deg, #C8A96E, #A8893E);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(200, 169, 110, 0.3);
    }
    .shoe-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
            max-width: 400px;
        }
        .login-visual {
            padding: 2rem;
            min-height: 200px;
        }
        .login-form {
            padding: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- Visual Panel -->
        <div class="login-visual">
            <div style="position: relative; z-index: 1; text-align: center;">
                <i class="fas fa-shoe-prints shoe-icon"></i>
                <h2 class="mb-3">Welcome Back</h2>
                <p class="mb-0">Step into comfort with SoulMates Footwear</p>
            </div>
        </div>
        
        <!-- Form Panel -->
        <div class="login-form">
            <div class="text-center mb-4">
                <h1 class="brand-title">Login</h1>
                <p class="brand-subtitle">Sign in to your account</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input id="email" type="email" 
                               class="form-control border-start-0 @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus 
                               placeholder="Enter your email">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input id="password" type="password" 
                               class="form-control border-start-0 @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Enter your password">
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </div>
                
                <!-- Forgot Password -->
                <div class="text-center">
                    <a href="#" class="text-decoration-none">Forgot your password?</a>
                </div>
            </form>
            
            <!-- Register Link -->
            <div class="text-center mt-4">
                <p class="mb-0">Don't have an account? 
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                        Create one here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
