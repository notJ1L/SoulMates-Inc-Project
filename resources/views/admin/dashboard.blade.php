@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('topbar-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-sm" style="background:var(--accent);color:var(--black);font-size:0.78rem;font-weight:700;border-radius:3px;padding:0.4rem 1rem;border:none;">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
@endsection

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-bag"></i></div>
            <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
            <div class="stat-label">Products</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Registered Users</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-value">₱{{ number_format($totalRevenue ?? 0, 0) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Recent Orders --}}
    <div class="col-lg-7">
        <div style="background:var(--white);border-radius:6px;border:1px solid rgba(0,0,0,0.07);overflow:hidden;">
            <div style="padding:1.1rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.07); display:flex; justify-content:space-between; align-items:center;">
                <div style="font-family:var(--font-display);font-size:1.05rem;font-weight:700;">Recent Orders</div>
                <a href="{{ route('admin.orders.index') }}" style="font-size:0.75rem;color:var(--accent);text-decoration:none;">View all →</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td><span style="font-family:var(--font-mono);font-size:0.75rem;">#{{ $order->id }}</span></td>
                            <td>
                                <div style="font-size:0.85rem;font-weight:600;">{{ $order->user->name }}</div>
                                <div style="font-size:0.72rem;color:var(--warm-gray);">{{ $order->user->email }}</div>
                            </td>
                            <td><span style="font-family:var(--font-mono);font-size:0.85rem;">₱{{ number_format($order->total, 2) }}</span></td>
                            <td>
                                <span class="badge-status badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td><span style="font-size:0.75rem;color:var(--warm-gray);">{{ $order->created_at->format('M d') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;color:var(--warm-gray);padding:2rem;font-size:0.85rem;">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-5">
        {{-- Pending orders alert --}}
        @if(($pendingOrders ?? 0) > 0)
        <div style="background:rgba(200,169,110,0.12);border:1px solid rgba(200,169,110,0.3);border-radius:6px;padding:1.25rem;margin-bottom:1rem;display:flex;align-items:center;gap:1rem;">
            <div style="width:44px;height:44px;background:var(--accent);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                <i class="bi bi-clock"></i>
            </div>
            <div>
                <div style="font-weight:700;font-size:0.9rem;">{{ $pendingOrders }} Pending Order{{ $pendingOrders > 1 ? 's' : '' }}</div>
                <div style="font-size:0.78rem;color:var(--warm-gray);margin-top:2px;">Need your attention</div>
            </div>
            <a href="{{ route('admin.orders.index') }}?status=pending" style="margin-left:auto;font-size:0.75rem;color:var(--accent);text-decoration:none;white-space:nowrap;">View →</a>
        </div>
        @endif

        {{-- Low stock alert --}}
        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
        <div style="background:var(--white);border-radius:6px;border:1px solid rgba(0,0,0,0.07);overflow:hidden;">
            <div style="padding:1rem 1.5rem;border-bottom:1px solid rgba(0,0,0,0.07);display:flex;justify-content:space-between;align-items:center;">
                <div style="font-family:var(--font-display);font-size:1rem;font-weight:700;">Low Stock Alert</div>
                <span style="font-family:var(--font-mono);font-size:0.65rem;background:rgba(192,57,43,0.12);color:var(--red);padding:3px 10px;border-radius:20px;">{{ $lowStockProducts->count() }} items</span>
            </div>
            <div style="padding:0.5rem 0;">
                @foreach($lowStockProducts as $p)
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 1.25rem;border-bottom:1px solid rgba(0,0,0,0.04);">
                    <div style="flex:1;">
                        <div style="font-size:0.83rem;font-weight:600;">{{ $p->name }}</div>
                        <div style="font-size:0.7rem;color:var(--warm-gray);">{{ $p->brand }}</div>
                    </div>
                    <span style="font-family:var(--font-mono);font-size:0.75rem;color:{{ $p->stock == 0 ? 'var(--red)' : '#c09b00' }};">
                        {{ $p->stock }} left
                    </span>
                    <a href="{{ route('admin.products.edit', $p->id) }}" style="font-size:0.75rem;color:var(--accent);">Edit</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Recent registrations --}}
        <div style="background:var(--white);border-radius:6px;border:1px solid rgba(0,0,0,0.07);overflow:hidden;margin-top:1rem;">
            <div style="padding:1rem 1.5rem;border-bottom:1px solid rgba(0,0,0,0.07);display:flex;justify-content:space-between;align-items:center;">
                <div style="font-family:var(--font-display);font-size:1rem;font-weight:700;">New Users</div>
                <a href="{{ route('admin.users.index') }}" style="font-size:0.75rem;color:var(--accent);text-decoration:none;">View all →</a>
            </div>
            <div style="padding:0.5rem 0;">
                @forelse($recentUsers ?? [] as $user)
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 1.25rem;border-bottom:1px solid rgba(0,0,0,0.04);">
                    <div style="width:34px;height:34px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;color:var(--black);flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:0.83rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                        <div style="font-size:0.7rem;color:var(--warm-gray);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->email }}</div>
                    </div>
                    <span style="font-size:0.68rem;color:var(--warm-gray);">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                    <div style="text-align:center;padding:1.5rem;color:var(--warm-gray);font-size:0.83rem;">No users yet.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
