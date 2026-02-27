@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="admin-form-card">
            <div class="card-head">
                <h5 class="mb-0">
                    <i class="bi bi-person-gear me-2"></i>Edit User: {{ $user->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" 
                                   name="phone" 
                                   class="form-control" 
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Administrator
                                </option>
                            </select>
                            @error('role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" 
                                  class="form-control" 
                                  rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Account Status</label>
                        <div class="form-check">
                            <input type="radio" 
                                   name="is_active" 
                                   value="1" 
                                   id="active" 
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">
                                Active
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="radio" 
                                   name="is_active" 
                                   value="0" 
                                   id="inactive" 
                                   {{ !old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="inactive">
                                Inactive
                            </label>
                        </div>
                        @error('is_active')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="admin-form-card">
            <div class="card-head">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="admin-avatar mx-auto" style="width: 80px; height: 80px;">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted" style="width: 100px;">User ID:</td>
                        <td class="font-mono">#{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Registered:</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Last Updated:</td>
                        <td>{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                    @if($user->orders->count() > 0)
                    <tr>
                        <td class="text-muted">Total Orders:</td>
                        <td>{{ $user->orders->count() }}</td>
                    </tr>
                    @endif
                </table>
                
                @if($user->id === auth()->id())
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> You cannot deactivate your own account.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
