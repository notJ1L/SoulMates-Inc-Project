@extends('layouts.app')

@section('title', 'Home — SoulMates Inc.')

@section('head')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: white;
        margin-bottom: 1rem;
        line-height: 1.1;
    }
    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
    }
    .search-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .search-box {
        background: white;
        border-radius: 50px;
        padding: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .search-input {
        border: none;
        border-radius: 50px;
        padding: 15px 25px;
        font-size: 1.1rem;
        width: 100%;
        outline: none;
    }
    .search-btn {
        background: linear-gradient(135deg, #C8A96E, #A8893E);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(200, 169, 110, 0.4);
    }
    .section-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #0D0D0D;
        margin-bottom: 1rem;
        position: relative;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #C8A96E, #A8893E);
        border-radius: 2px;
    }
    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
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
    }
    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #C8A96E;
    }
    .brand-strip {
        background: #f8f9fa;
        padding: 3rem 0;
    }
    .brand-logo {
        height: 60px;
        object-fit: contain;
        filter: grayscale(100%);
        opacity: 0.6;
        transition: all 0.3s ease;
    }
    .brand-logo:hover {
        filter: grayscale(0%);
        opacity: 1;
    }
    .category-card {
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #C8A96E;
    }
    .category-icon {
        font-size: 3rem;
        color: #C8A96E;
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        .hero-subtitle {
            font-size: 1rem;
        }
        .section-title {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8 mx-auto text-center hero-content">
                <h1 class="hero-title">Step Into Style</h1>
                <p class="hero-subtitle">Discover the perfect pair that matches your unique journey</p>
                
                <!-- Search Bar -->
                <div class="search-container">
                    <form action="{{ route('search') }}" method="GET" class="search-box">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="search-input" 
                                   placeholder="Search for shoes, brands, or styles..."
                                   value="{{ request('search') }}">
                            <button class="btn search-btn" type="submit">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Featured Products</h2>
            <p class="text-muted">Handpicked selections from our premium collection</p>
        </div>
        
        <div class="row g-4">
            @forelse ($featuredProducts ?? [] as $product)
                <div class="col-md-6 col-lg-3">
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
                            <h5 class="product-name">{{ $product->name }}</h5>
                            <p class="text-muted small mb-2">{{ $product->category->name ?? 'Uncategorized' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No featured products available at the moment.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-lg btn-primary">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Shop by Category</h2>
            <p class="text-muted">Find exactly what you're looking for</p>
        </div>
        
        <div class="row g-4">
            @forelse ($categories ?? [] as $category)
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-shoe-prints"></i>
                        </div>
                        <h4>{{ $category->name }}</h4>
                        <p class="text-muted">{{ $category->products_count ?? 0 }} Products</p>
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                           class="btn btn-outline-primary">
                            Shop Now
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No categories available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Brand Strip -->
<section class="brand-strip">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Top Brands</h3>
        </div>
        <div class="row align-items-center">
            @forelse ($brands ?? [] as $brand)
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="text-center">
                        <img src="{{ asset('images/brands/' . $brand->name . '.png') }}" 
                             alt="{{ $brand->name }}" 
                             class="brand-logo img-fluid">
                        <p class="small text-muted mt-2">{{ $brand->name }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No brands available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
