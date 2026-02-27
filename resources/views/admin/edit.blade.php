@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('topbar-actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.78rem;border-radius:3px;">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="admin-form-card">
            <div class="card-head d-flex align-items-center gap-3">
                <div style="width:44px;height:44px;border-radius:50%;overflow:hidden;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:var(--black);flex-shrink:0;">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h5>{{ $user->name }}</h5>
                    <div style="font-size:0.72rem;color:rgba(255,255,255,0.5);font-family:var(--font-mono);">{{ $user->email }}</div>
                </div>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e) <li style="font-size:0.83rem;">{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="user"  {{ $user->role === 'user'  ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <small style="font-size:0.72rem;color:var(--warm-gray);">You cannot change your own role.</small>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <hr style="border-color:rgba(0,0,0,0.08);">
                    <p style="font-size:0.78rem;color:var(--warm-gray);">Leave password blank to keep unchanged.</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
