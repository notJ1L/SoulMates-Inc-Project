@extends('layouts.app')

@section('title', 'Shop — SoulMates Inc.')

@section('head')
<style>
    .filter-sidebar {
        position: sticky;
        top: 90px;
    }
    .filter-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    .product-image {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }
    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0D0D0D;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #C8A96E;
    }
    .filter-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6B6560;
        margin-bottom: 1rem;
    }
    .brand-item, .category-item {
        display: block;
        padding: 0.5rem 0;
        color: #0D0D0D;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        padding-left: 0.75rem;
    }
    .brand-item:hover, .category-item:hover {
        color: #C8A96E;
        background: rgba(200, 169, 110, 0.05);
        border-left-color: #C8A96E;
    }
    .brand-item.active, .category-item.active {
        color: #C8A96E;
        border-left-color: #C8A96E;
        font-weight: 600;
    }
    .results-count {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #6B6560;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <!-- Search Results -->
                @if(request('search'))
                    <div class="filter-card">
                        <h5 class="filter-title">Search Results</h5>
                        <p class="text-muted small">
                            Showing results for "<strong>{{ request('search') }}</strong>"
                        </p>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Search
                        </a>
                    </div>
                @endif

                <!-- Categories Filter -->
                <div class="filter-card">
                    <h5 class="filter-title">Categories</h5>
                    <div class="mb-3">
                        <a href="{{ route('products.index') }}" 
                           class="category-item {{ !request('category_id') ? 'active' : '' }}">
                            All Categories
                        </a>
                        @forelse ($categories ?? [] as $category)
                            <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                               class="category-item {{ request('category_id') == $category->id ? 'active' : '' }}">
                                {{ $category->name }} ({{ $category->products_count ?? 0 }})
                            </a>
                        @empty
                            <p class="text-muted small">No categories available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Brands Filter -->
                <div class="filter-card">
                    <h5 class="filter-title">Brands</h5>
                    <div class="mb-3">
                        <a href="{{ route('products.index') }}" 
                           class="brand-item {{ !request('brand_id') ? 'active' : '' }}">
                            All Brands
                        </a>
                        @forelse ($brands ?? [] as $brand)
                            <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}" 
                               class="brand-item {{ request('brand_id') == $brand->id ? 'active' : '' }}">
                                {{ $brand->name }} ({{ $brand->products_count ?? 0 }})
                            </a>
                        @empty
                            <p class="text-muted small">No brands available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <!-- Results Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="results-count">
                    @if(request('search'))
                        Found {{ $products->total() }} results for "{{ request('search') }}"
                    @else
                        Showing {{ $products->count() }} of {{ $products->total() }} products
                    @endif
                </div>
                
                <!-- Sort Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-sort me-2"></i>Sort By
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}">Name</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}">Price: Low to High</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}">Price: High to Low</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest First</a></li>
                    </ul>
                </div>
            </div>

            <!-- Products Grid -->
            @forelse ($products as $product)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card product-card">
                        @if($product->photos->first())
                            <img src="{{ asset('storage/' . $product->photos->first()->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image">
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image">
                        @endif
                        
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                            </div>
                            <h5 class="product-name">{{ $product->name }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($product->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <div>
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <button class="btn btn-sm btn-outline-success ms-1" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No products found</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            Try adjusting your search terms or browse our categories.
                        @else
                            No products available in this category.
                        @endif
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Browse All Products
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addToCart(productId) {
    // TODO: Implement cart functionality
    alert('Cart functionality will be implemented in the next phase.');
}
</script>
@endsection
    }
    .filter-title {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--warm-gray);
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid rgba(0,0,0,0.07);
    }
    .filter-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 0;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .filter-option input[type="radio"],
    .filter-option input[type="checkbox"] {
        accent-color: var(--accent);
        cursor: pointer;
    }
    .filter-count {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--warm-gray);
        margin-left: auto;
    }
    .active-filter {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--black);
        color: var(--accent);
        font-family: var(--font-mono);
        font-size: 0.68rem;
        letter-spacing: 0.08em;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        text-decoration: none;
    }
    .active-filter:hover { background: var(--accent); color: var(--black); }

    .sort-bar {
        display: flex;
        align-items: center;
        justify-content-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.07);
        border-radius: 4px;
        margin-bottom: 1.5rem;
    }
    .sort-bar .result-count {
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--warm-gray);
    }
    .sort-bar .result-count strong { color: var(--black); }

    /* Product card same as homepage */
    .product-card {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.07);
        border-radius: 4px;
        overflow: hidden;
        transition: all 0.25s ease;
        text-decoration: none;
        color: var(--black);
        display: block;
        height: 100%;
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        color: var(--black);
    }
    .product-card:hover .product-img { transform: scale(1.04); }
    .product-img-wrap {
        overflow: hidden;
        background: #F8F5EF;
        aspect-ratio: 4/3;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .product-img-placeholder { font-size: 3.5rem; opacity: 0.2; }
    .product-badge {
        position: absolute; top: 0.6rem; left: 0.6rem;
        background: var(--black); color: var(--accent);
        font-family: var(--font-mono); font-size: 0.58rem;
        letter-spacing: 0.1em; text-transform: uppercase;
        padding: 2px 8px; border-radius: 2px;
    }
    .product-info { padding: 1rem 1.1rem 1.1rem; }
    .product-brand { font-family: var(--font-mono); font-size: 0.6rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.2rem; }
    .product-name { font-family: var(--font-display); font-size: 1rem; font-weight: 700; line-height: 1.3; margin-bottom: 0.4rem; }
    .product-price { font-family: var(--font-mono); font-size: 1rem; font-weight: 700; }
    .product-rating { font-size: 0.72rem; color: var(--accent); margin-top: 0.3rem; }
    .product-rating span { color: var(--warm-gray); font-size: 0.68rem; margin-left: 3px; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--warm-gray);
    }
    .empty-state .empty-icon { font-size: 4rem; opacity: 0.25; margin-bottom: 1rem; }
    .empty-state h5 { font-family: var(--font-display); color: var(--black); }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Shop</li>
        </ol></nav>
        <h1>
            @if(request('search'))
                Results for "{{ request('search') }}"
            @elseif(request('brand'))
                {{ request('brand') }}
            @elseif(request('category'))
                {{ request('category') }}
            @else
                All Products
            @endif
        </h1>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        {{-- ===== SIDEBAR FILTERS ===== --}}
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- Active filters --}}
                    @if(request('brand') || request('category') || request('search'))
                        <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
                            <span style="font-size:0.75rem; color:var(--warm-gray);">Active:</span>
                            @if(request('search'))
                                <a href="{{ route('products.index') }}" class="active-filter">
                                    "{{ request('search') }}" <i class="bi bi-x"></i>
                                </a>
                            @endif
                            @if(request('brand'))
                                <a href="{{ route('products.index', array_merge(request()->except('brand'), [])) }}" class="active-filter">
                                    {{ request('brand') }} <i class="bi bi-x"></i>
                                </a>
                            @endif
                            @if(request('category'))
                                <a href="{{ route('products.index', array_merge(request()->except('category'), [])) }}" class="active-filter">
                                    {{ request('category') }} <i class="bi bi-x"></i>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Sort --}}
                    <div class="filter-card">
                        <div class="filter-title">Sort By</div>
                        @foreach(['latest' => 'Newest First', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'name' => 'Name A–Z'] as $val => $label)
                            <label class="filter-option">
                                <input type="radio" name="sort" value="{{ $val }}"
                                       {{ request('sort', 'latest') === $val ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    {{-- Category --}}
                    @if(isset($categories) && $categories->count() > 0)
                    <div class="filter-card">
                        <div class="filter-title">Category</div>
                        <label class="filter-option">
                            <input type="radio" name="category" value=""
                                   {{ !request('category') ? 'checked' : '' }}
                                   onchange="this.form.submit()"> All
                        </label>
                        @foreach($categories as $cat)
                            <label class="filter-option">
                                <input type="radio" name="category" value="{{ $cat->category }}"
                                       {{ request('category') === $cat->category ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                {{ $cat->category }}
                                <span class="filter-count">{{ $cat->total }}</span>
                            </label>
                        @endforeach
                    </div>
                    @endif

                    {{-- Brand --}}
                    @if(isset($brands) && $brands->count() > 0)
                    <div class="filter-card">
                        <div class="filter-title">Brand</div>
                        <label class="filter-option">
                            <input type="radio" name="brand" value=""
                                   {{ !request('brand') ? 'checked' : '' }}
                                   onchange="this.form.submit()"> All
                        </label>
                        @foreach($brands as $br)
                            <label class="filter-option">
                                <input type="radio" name="brand" value="{{ $br->brand }}"
                                       {{ request('brand') === $br->brand ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                {{ $br->brand }}
                                <span class="filter-count">{{ $br->total }}</span>
                            </label>
                        @endforeach
                    </div>
                    @endif

                </form>
            </div>
        </div>

        {{-- ===== PRODUCT GRID ===== --}}
        <div class="col-lg-9">
            <div class="sort-bar d-flex justify-content-between align-items-center">
                <span class="result-count">
                    <strong>{{ $products->total() }}</strong> product{{ $products->total() !== 1 ? 's' : '' }} found
                </span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Clear Filters
                </a>
            </div>

            @if($products->count() > 0)
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-sm-6 col-xl-4">
                            <a href="{{ route('products.show', $product->id) }}" class="product-card">
                                <div class="product-img-wrap">
                                    @if($product->photos && $product->photos->first())
                                        <img src="{{ asset('storage/product_photos/' . $product->photos->first()->filename) }}"
                                             alt="{{ $product->name }}" class="product-img">
                                    @else
                                        <span class="product-img-placeholder">👟</span>
                                    @endif
                                    @if($product->created_at->diffInDays() < 14)
                                        <span class="product-badge">New</span>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <div class="product-brand">{{ $product->brand }}</div>
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                                        @if($product->stock <= 5 && $product->stock > 0)
                                            <small style="font-size:0.68rem; color:var(--red);">Only {{ $product->stock }} left</small>
                                        @elseif($product->stock == 0)
                                            <small style="font-size:0.68rem; color:var(--warm-gray);">Out of stock</small>
                                        @endif
                                    </div>
                                    @if($product->reviews_count > 0)
                                        <div class="product-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($product->reviews_avg_rating) ? '-fill' : '' }}"></i>
                                            @endfor
                                            <span>({{ $product->reviews_count }})</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif

            @else
                <div class="empty-state">
                    <div class="empty-icon">🔍</div>
                    <h5>No products found</h5>
                    <p class="mt-2" style="font-size:0.88rem;">
                        Try adjusting your search or filters.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Browse All</a>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
