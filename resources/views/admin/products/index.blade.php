@extends('layouts.admin')

@section('page-title', 'Products')

@section('topbar-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
@endsection

@section('content')
<div class="admin-form-card">
    <div class="card-body p-0">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($product->photos->first())
                                    <img src="{{ asset('storage/' . $product->photos->first()->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div class="me-3 bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; border-radius: 6px;">
                                        <i class="fas fa-box text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="text-muted small">{{ Str::limit($product->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $product->brand->name ?? 'No Brand' }}
                            </span>
                        </td>
                        <td>
                            <span class="font-mono fw-semibold">${{ number_format($product->price, 2) }}</span>
                        </td>
                        <td>
                            <span class="font-mono {{ ($product->stock ?? 0) <= 5 ? 'text-warning' : '' }}">
                                {{ $product->stock ?? 0 }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status {{ ($product->stock ?? 0) > 0 ? 'badge-active' : 'badge-inactive' }}">
                                {{ ($product->stock ?? 0) > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No products found</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i>Add Your First Product
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif
@endsection
