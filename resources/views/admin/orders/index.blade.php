@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')
<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex gap-3 align-items-center">
            <form method="GET" class="d-flex gap-2 align-items-center">
                @csrf
                
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by order ID or customer name..." 
                       value="{{ request('search') }}">
                
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="admin-form-card">
    <div class="card-body p-0">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <div class="fw-semibold font-mono">#{{ $order->id }}</div>
                            <div class="text-muted small">{{ $order->created_at->format('M d, Y') }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name }}</div>
                            <div class="text-muted small">{{ $order->user->email }}</div>
                            @if($order->user->phone)
                                <div class="text-muted small">{{ $order->user->phone }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->orderItems->count() }} items</div>
                            <div class="text-muted small">
                                {{ $order->orderItems->first()->product->name ?? 'No items' }}
                                @if($order->orderItems->count() > 1)
                                    and {{ $order->orderItems->count() - 1 }} more
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="font-mono fw-semibold">${{ number_format($order->total, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $order->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#orderModal{{ $order->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($order->status !== 'completed')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-success"
                                            title="Mark as Completed"
                                            onclick="return confirm('Mark this order as completed?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-shopping-bag fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No orders found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
@endif

<!-- Order Details Modal -->
@foreach ($orders as $order)
<div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bag-check me-2"></i>Order #{{ $order->id }} Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Customer Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted" style="width: 100px;">Name:</td>
                                <td>{{ $order->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td>{{ $order->user->email }}</td>
                            </tr>
                            @if($order->user->phone)
                            <tr>
                                <td class="text-muted">Phone:</td>
                                <td>{{ $order->user->phone }}</td>
                            </tr>
                            @endif
                            @if($order->user->address)
                            <tr>
                                <td class="text-muted">Address:</td>
                                <td>{{ $order->user->address }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Order Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted" style="width: 100px;">Status:</td>
                                <td>
                                    <span class="badge-status badge-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Order Date:</td>
                                <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Subtotal:</td>
                                <td class="font-mono">${{ number_format($order->total, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total:</td>
                                <td class="font-mono fw-bold">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h6 class="fw-semibold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product->name }}</div>
                                        <div class="text-muted small">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                                    </td>
                                    <td class="font-mono">${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="font-mono fw-semibold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Status Update Form -->
                @if($order->status !== 'completed')
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="fw-semibold mb-3">Update Order Status</h6>
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <select name="status" class="form-select" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-sync me-2"></i>Update Status
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">View All Orders</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
