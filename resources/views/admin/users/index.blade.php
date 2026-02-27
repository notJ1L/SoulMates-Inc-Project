@extends('layouts.admin')

@section('page-title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">User Management</h3>
        <p class="text-muted mb-0">Manage all registered users</p>
    </div>
    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Add New User
        </a>
    </div>
</div>

<!-- Users Table -->
<div class="admin-form-card">
    <div class="card-body p-0">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="admin-avatar me-3">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    @if($user->phone)
                                        <div class="text-muted small">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-status badge-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($user->is_active)
                                    <form action="{{ route('admin.users.deactivate', $user) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to deactivate this user?')">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-warning"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.activate', $user) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to activate this user?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No users found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
@endif
@endsection
