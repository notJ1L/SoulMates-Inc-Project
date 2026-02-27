@extends('layouts.app')

@section('title', 'Register — SoulMates Inc.')

@section('head')
<style>
    .register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .register-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1200px;
        width: 100%;
        display: flex;
        min-height: 700px;
    }
    .register-visual {
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
    .register-visual::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    .register-form {
        flex: 1.5;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
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
    .btn-register {
        background: linear-gradient(135deg, #C8A96E, #A8893E);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(200, 169, 110, 0.3);
    }
    .shoe-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #C8A96E;
        object-fit: cover;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .photo-upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .photo-upload-area:hover {
        border-color: #C8A96E;
        background: rgba(200, 169, 110, 0.05);
    }
    @media (max-width: 768px) {
        .register-card {
            flex-direction: column;
            max-width: 500px;
        }
        .register-visual {
            padding: 2rem;
            min-height: 200px;
        }
        .register-form {
            padding: 2rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

@section('content')
<div class="register-container">
    <div class="register-card">
        <!-- Visual Panel -->
        <div class="register-visual">
            <div style="position: relative; z-index: 1; text-align: center;">
                <i class="fas fa-shoe-prints shoe-icon"></i>
                <h2 class="mb-3">Join Our Community</h2>
                <p class="mb-0">Step into style with SoulMates Footwear</p>
            </div>
        </div>
        
        <!-- Form Panel -->
        <div class="register-form">
            <div class="text-center mb-4">
                <h1 class="brand-title">Create Account</h1>
                <p class="brand-subtitle">Join our exclusive footwear community</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Profile Photo -->
                <div class="mb-4 text-center">
                    <img id="photoPreview" 
                         src="{{ asset('images/default-avatar.png') }}" 
                         alt="Profile Preview" 
                         class="photo-preview" 
                         style="display: none;">
                    <div class="photo-upload-area" onclick="document.getElementById('profile_photo').click()">
                        <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">Click to upload profile photo</p>
                        <small class="text-muted">Optional • Max 2MB • JPG, PNG, GIF</small>
                    </div>
                    <input type="file" 
                           id="profile_photo" 
                           name="profile_photo" 
                           class="d-none" 
                           accept="image/*"
                           onchange="previewPhoto(this)">
                    @error('profile_photo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input id="name" type="text" 
                                   class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name" 
                                   autofocus 
                                   placeholder="Enter your full name">
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="col-md-6 mb-3">
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
                                   placeholder="Enter your email">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input id="password" type="password" 
                                   class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Create password">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input id="password_confirmation" type="password" 
                                   class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Confirm password">
                        </div>
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-phone text-muted"></i>
                            </span>
                            <input id="phone" type="tel" 
                                   class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="Enter phone number">
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Address -->
                    <div class="col-md-6 mb-4">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-home text-muted"></i>
                            </span>
                            <input id="address" type="text" 
                                   class="form-control border-start-0 @error('address') is-invalid @enderror" 
                                   name="address" 
                                   value="{{ old('address') }}" 
                                   placeholder="Enter your address">
                        </div>
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-register btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                </div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <p class="mb-0">Already have an account? 
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
