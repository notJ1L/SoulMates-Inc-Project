@extends('layouts.app')

@section('title', $product->name . ' — SoulMates Inc.')

@section('head')
<style>
    .product-gallery {
        position: sticky;
        top: 90px;
    }
    .main-photo {
        background: #F8F5EF;
        border-radius: 4px;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 0.75rem;
        border: 1px solid rgba(0,0,0,0.07);
    }
    .main-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .main-photo:hover img { transform: scale(1.05); }
    .main-photo-placeholder { font-size: 8rem; opacity: 0.15; }

    .thumb-strip { display: flex; gap: 0.6rem; flex-wrap: wrap; }
    .thumb {
        width: 72px;
        height: 72px;
        border-radius: 3px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        background: #F8F5EF;
        transition: border-color 0.2s;
        flex-shrink: 0;
    }
    .thumb.active, .thumb:hover { border-color: var(--accent); }
    .thumb img { width: 100%; height: 100%; object-fit: cover; }

    /* Product info */
    .product-detail-brand {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.4rem;
    }
    .product-detail-name {
        font-family: var(--font-display);
        font-size: 2.2rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 1rem;
    }
    .product-detail-price {
        font-family: var(--font-mono);
        font-size: 2rem;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 0.5rem;
    }

    .rating-summary {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .stars-display { color: var(--accent); font-size: 0.9rem; }
    .rating-text { font-size: 0.82rem; color: var(--warm-gray); }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        margin-bottom: 1.5rem;
    }
    .stock-badge.in-stock { background: rgba(39,174,96,0.12); color: #1e8a49; }
    .stock-badge.low-stock { background: rgba(241,196,15,0.15); color: #c09b00; }
    .stock-badge.out-stock { background: rgba(192,57,43,0.1); color: var(--red); }

    .product-meta {
        background: rgba(0,0,0,0.03);
        border-radius: 3px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .meta-row {
        display: flex;
        font-size: 0.83rem;
        padding: 0.3rem 0;
    }
    .meta-row:not(:last-child) { border-bottom: 1px solid rgba(0,0,0,0.05); }
    .meta-label {
        font-weight: 600;
        color: var(--warm-gray);
        width: 90px;
        flex-shrink: 0;
        font-size: 0.78rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .qty-control {
        display: flex;
        align-items: center;
        border: 1.5px solid rgba(0,0,0,0.15);
        border-radius: 2px;
        overflow: hidden;
        width: fit-content;
    }
    .qty-btn {
        width: 40px;
        height: 44px;
        background: #f3f0ea;
        border: none;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-btn:hover { background: var(--accent); color: var(--black); }
    .qty-input {
        width: 52px;
        height: 44px;
        border: none;
        border-left: 1.5px solid rgba(0,0,0,0.1);
        border-right: 1.5px solid rgba(0,0,0,0.1);
        text-align: center;
        font-family: var(--font-mono);
        font-size: 0.95rem;
        font-weight: 700;
        background: var(--white);
    }
    .qty-input:focus { outline: none; }

    .btn-add-cart {
        background: var(--black);
        color: var(--white);
        border: 2px solid var(--black);
        padding: 0.9rem 2rem;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        border-radius: 2px;
        cursor: pointer;
        transition: all 0.22s;
        flex: 1;
    }
    .btn-add-cart:hover { background: var(--accent); border-color: var(--accent); color: var(--black); }
    .btn-add-cart:disabled { opacity: 0.5; cursor: not-allowed; }

    /* ============================================
       REVIEWS
    ============================================ */
    .reviews-section { padding: 3rem 0; background: var(--white); margin-top: 3rem; }

    .review-card {
        border: 1px solid rgba(0,0,0,0.07);
        border-radius: 4px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: var(--cream);
    }
    .reviewer-name {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
    }
    .review-date {
        font-family: var(--font-mono);
        font-size: 0.65rem;
        color: var(--warm-gray);
        letter-spacing: 0.08em;
    }
    .review-stars { color: var(--accent); font-size: 0.82rem; margin: 0.5rem 0; }
    .review-body { font-size: 0.87rem; line-height: 1.6; color: #3a3530; }

    .review-form-card {
        background: var(--cream);
        border: 1px solid rgba(200,169,110,0.3);
        border-radius: 4px;
        padding: 1.75rem;
    }
    .star-picker { display: flex; gap: 0.25rem; margin-bottom: 0.5rem; }
    .star-picker input { display: none; }
    .star-picker label {
        font-size: 1.6rem;
        cursor: pointer;
        color: #ccc;
        transition: color 0.15s;
        line-height: 1;
    }
    .star-picker input:checked ~ label,
    .star-picker label:hover,
    .star-picker label:hover ~ label { color: var(--accent); }
    .star-picker { flex-direction: row-reverse; }
    .star-picker label:hover,
    .star-picker label:hover ~ label { color: var(--accent); }

    /* Related products */
    .related-card {
        border: 1px solid rgba(0,0,0,0.07);
        border-radius: 4px;
        overflow: hidden;
        text-decoration: none;
        color: var(--black);
        display: block;
        transition: all 0.2s;
        background: var(--white);
    }
    .related-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); color: var(--black); }
    .related-img { aspect-ratio: 4/3; background: #F8F5EF; overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .related-img img { width: 100%; height: 100%; object-fit: cover; }
    .related-info { padding: 0.75rem; }
    .related-name { font-family: var(--font-display); font-size: 0.9rem; font-weight: 700; }
    .related-price { font-family: var(--font-mono); font-size: 0.85rem; margin-top: 0.2rem; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="page-header" style="padding:1.5rem 0 1.25rem;">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Shop</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol></nav>
    </div>
</div>

<div class="container py-4">
    <div class="row g-5">

        {{-- ===== PHOTO GALLERY ===== --}}
        <div class="col-lg-5">
            <div class="product-gallery">
                <div class="main-photo" id="mainPhoto">
                    @if($product->photos && $product->photos->first())
                        <img src="{{ asset('storage/product_photos/' . $product->photos->first()->filename) }}"
                             alt="{{ $product->name }}" id="mainPhotoImg">
                    @else
                        <span class="main-photo-placeholder">👟</span>
                    @endif
                </div>
                @if($product->photos && $product->photos->count() > 1)
                    <div class="thumb-strip">
                        @foreach($product->photos as $i => $photo)
                            <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                                 onclick="switchPhoto(this, '{{ asset('storage/product_photos/' . $photo->filename) }}')">
                                <img src="{{ asset('storage/product_photos/' . $photo->filename) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== PRODUCT DETAILS ===== --}}
        <div class="col-lg-7">
            <div class="product-detail-brand">{{ $product->brand }}</div>
            <h1 class="product-detail-name">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="rating-summary">
                <div class="stars-display">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($product->reviews_avg_rating ?? 0) ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <span class="rating-text">
                    {{ number_format($product->reviews_avg_rating ?? 0, 1) }} / 5.0
                    ({{ $product->reviews_count }} review{{ $product->reviews_count !== 1 ? 's' : '' }})
                </span>
            </div>

            <div class="product-detail-price">₱{{ number_format($product->price, 2) }}</div>

            {{-- Stock --}}
            @if($product->stock > 5)
                <span class="stock-badge in-stock"><i class="bi bi-check-circle-fill"></i> In Stock ({{ $product->stock }} available)</span>
            @elseif($product->stock > 0)
                <span class="stock-badge low-stock"><i class="bi bi-exclamation-circle-fill"></i> Only {{ $product->stock }} left!</span>
            @else
                <span class="stock-badge out-stock"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
            @endif

            {{-- Description --}}
            @if($product->description)
                <p style="font-size:0.9rem; line-height:1.75; color:#4a4540; margin-bottom:1.5rem;">
                    {{ $product->description }}
                </p>
            @endif

            {{-- Meta --}}
            <div class="product-meta">
                @if($product->category)
                <div class="meta-row">
                    <span class="meta-label">Category</span>
                    <a href="{{ route('products.index') }}?category={{ urlencode($product->category) }}"
                       style="color:var(--accent); text-decoration:none;">{{ $product->category }}</a>
                </div>
                @endif
                @if($product->size)
                <div class="meta-row">
                    <span class="meta-label">Size</span>
                    <span>{{ $product->size }}</span>
                </div>
                @endif
                @if($product->color)
                <div class="meta-row">
                    <span class="meta-label">Color</span>
                    <span>{{ $product->color }}</span>
                </div>
                @endif
            </div>

            {{-- Add to Cart --}}
            @auth
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                                <input type="number" name="quantity" id="qtyInput" class="qty-input"
                                       value="1" min="1" max="{{ $product->stock }}">
                                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                            </div>
                            <button type="submit" class="btn-add-cart">
                                <i class="bi bi-bag-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </form>
                @else
                    <button class="btn-add-cart" disabled>Out of Stock</button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none">
                    <i class="bi bi-lock me-2"></i>Login to Purchase
                </a>
            @endauth

        </div>
    </div>
</div>

{{-- ===== REVIEWS SECTION ===== --}}
<div class="reviews-section">
    <div class="container">
        <div class="row g-5">

            {{-- Reviews List --}}
            <div class="col-lg-7">
                <span class="section-label">Customer Reviews</span>
                <hr class="divider-accent">
                <h3 style="font-family:var(--font-display); font-size:1.6rem; margin-bottom:1.5rem;">
                    What People Are Saying
                </h3>

                @if($product->reviews->count() > 0)
                    @foreach($product->reviews as $review)
                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="reviewer-name">{{ $review->user->name }}</div>
                                    <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
                                </div>
                                @auth
                                    @if(auth()->id() === $review->user_id)
                                        <a href="{{ route('reviews.edit', $review->id) }}"
                                           class="btn btn-sm" style="font-size:0.72rem; color:var(--warm-gray);">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                    @endif
                                @endauth
                            </div>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <div class="review-body">{{ $review->body }}</div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align:center; padding:3rem 0; color:var(--warm-gray);">
                        <div style="font-size:3rem; opacity:0.2; margin-bottom:0.75rem;">💬</div>
                        <p style="font-size:0.88rem;">No reviews yet. Be the first!</p>
                    </div>
                @endif
            </div>

            {{-- Write Review --}}
            <div class="col-lg-5">
                <span class="section-label">Leave a Review</span>
                <hr class="divider-accent">

                @auth
                    @if($canReview)
                        <div class="review-form-card">
                            <h5 style="font-family:var(--font-display); margin-bottom:1.25rem;">Share your experience</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="mb-3">
                                    <label class="form-label">Your Rating</label>
                                    <div class="star-picker">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                                   {{ old('rating') == $i ? 'checked' : '' }} required>
                                            <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">★</label>
                                        @endfor
                                    </div>
                                    @error('rating') <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Your Review</label>
                                    <textarea name="body" class="form-control @error('body') is-invalid @enderror"
                                              rows="4" placeholder="Tell us what you think…" required>{{ old('body') }}</textarea>
                                    @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @elseif($hasReviewed)
                        <div class="review-form-card text-center" style="color:var(--warm-gray);">
                            <i class="bi bi-check-circle" style="font-size:2rem; color:var(--accent);"></i>
                            <p class="mt-2" style="font-size:0.88rem;">You've already reviewed this product.</p>
                        </div>
                    @else
                        <div class="review-form-card text-center" style="color:var(--warm-gray);">
                            <i class="bi bi-bag" style="font-size:2rem; opacity:0.3;"></i>
                            <p class="mt-2" style="font-size:0.87rem;">
                                You must purchase this product before reviewing it.
                            </p>
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary mt-2">Browse Products</a>
                        </div>
                    @endif
                @else
                    <div class="review-form-card text-center" style="color:var(--warm-gray);">
                        <i class="bi bi-lock" style="font-size:2rem; opacity:0.3;"></i>
                        <p class="mt-2" style="font-size:0.87rem;">Please login to leave a review.</p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary mt-2">Login</a>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</div>

{{-- ===== RELATED PRODUCTS ===== --}}
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div class="container py-4 mb-3">
    <span class="section-label">More like this</span>
    <hr class="divider-accent">
    <h3 style="font-family:var(--font-display); font-size:1.5rem; margin-bottom:1.5rem;">Related Products</h3>
    <div class="row g-3">
        @foreach($relatedProducts as $related)
            <div class="col-6 col-md-3">
                <a href="{{ route('products.show', $related->id) }}" class="related-card">
                    <div class="related-img">
                        @if($related->photos && $related->photos->first())
                            <img src="{{ asset('storage/product_photos/' . $related->photos->first()->filename) }}" alt="{{ $related->name }}">
                        @else
                            <span style="font-size:2.5rem; opacity:0.2;">👟</span>
                        @endif
                    </div>
                    <div class="related-info">
                        <div class="related-name">{{ $related->name }}</div>
                        <div class="related-price">₱{{ number_format($related->price, 2) }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    function switchPhoto(el, src) {
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        const img = document.getElementById('mainPhotoImg');
        if (img) img.src = src;
    }

    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        const max = parseInt(input.max) || 99;
        let val = parseInt(input.value) + delta;
        val = Math.max(1, Math.min(max, val));
        input.value = val;
    }
</script>
@endsection
