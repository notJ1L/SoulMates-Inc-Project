@extends('layouts.admin')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('topbar-actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.78rem;border-radius:3px;">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
    </a>
@endsection

@section('content')
<div class="row g-4">

    {{-- Main Form --}}
    <div class="col-lg-8">
        <div class="admin-form-card">
            <div class="card-head">
                <h5>{{ isset($product) ? 'Edit: ' . $product->name : 'Product Information' }}</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e) <li style="font-size:0.83rem;">{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
                      method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    @if(isset($product)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Product Name <span style="color:var(--red);">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name ?? '') }}" required placeholder="e.g. Air Max 270">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Brand <span style="color:var(--red);">*</span></label>
                            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                                   value="{{ old('brand', $product->brand ?? '') }}" required placeholder="e.g. Nike">
                            @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control"
                                   value="{{ old('category', $product->category ?? '') }}" placeholder="e.g. Running, Casual">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Describe the product…">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Price (₱) <span style="color:var(--red);">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price ?? '') }}"
                                   min="0" step="0.01" required placeholder="0.00">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock / Quantity <span style="color:var(--red);">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $product->stock ?? 0) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Size</label>
                            <input type="text" name="size" class="form-control"
                                   value="{{ old('size', $product->size ?? '') }}" placeholder="e.g. 7, 8, 9, 10">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control"
                               value="{{ old('color', $product->color ?? '') }}" placeholder="e.g. Black/White">
                    </div>

                    {{-- Photo Upload --}}
                    <div style="border-top:1px solid rgba(0,0,0,0.08);padding-top:1.5rem;margin-top:0.5rem;">
                        <label class="form-label d-block mb-2">
                            Product Photos
                            <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--warm-gray);letter-spacing:0.08em;font-weight:400;"> (Upload multiple — side view, top, sole, etc.)</span>
                        </label>

                        {{-- Existing photos --}}
                        @if(isset($product) && $product->photos && $product->photos->count() > 0)
                            <div style="margin-bottom:1rem;">
                                <p style="font-size:0.75rem;color:var(--warm-gray);margin-bottom:0.6rem;">Existing photos (click X to remove):</p>
                                <div class="d-flex flex-wrap gap-2" id="existingPhotos">
                                    @foreach($product->photos as $photo)
                                        <div class="existing-photo" id="photo-{{ $photo->id }}"
                                             style="position:relative;width:80px;height:80px;border-radius:4px;overflow:hidden;border:1.5px solid rgba(0,0,0,0.1);">
                                            <img src="{{ asset('storage/product_photos/' . $photo->filename) }}"
                                                 style="width:100%;height:100%;object-fit:cover;">
                                            <button type="button"
                                                    onclick="removeExistingPhoto({{ $photo->id }}, this)"
                                                    style="position:absolute;top:2px;right:2px;background:rgba(192,57,43,0.9);border:none;border-radius:50%;width:20px;height:20px;color:white;font-size:0.6rem;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Upload area --}}
                        <div id="dropzone"
                             style="border:2px dashed rgba(200,169,110,0.4);border-radius:4px;padding:2rem;text-align:center;cursor:pointer;transition:all 0.2s;background:rgba(200,169,110,0.03);"
                             onclick="document.getElementById('photoInput').click()"
                             ondragover="this.style.borderColor='var(--accent)';this.style.background='rgba(200,169,110,0.08)';event.preventDefault();"
                             ondragleave="this.style.borderColor='rgba(200,169,110,0.4)';this.style.background='rgba(200,169,110,0.03)';"
                             ondrop="handleDrop(event)">
                            <input type="file" id="photoInput" name="photos[]" multiple accept="image/*"
                                   style="display:none;" onchange="previewPhotos(this)">
                            <div id="dropPlaceholder">
                                <i class="bi bi-cloud-upload" style="font-size:2rem;color:var(--accent);opacity:0.6;"></i>
                                <p style="font-size:0.85rem;color:var(--warm-gray);margin-top:0.5rem;">
                                    Drag & drop photos here, or <strong style="color:var(--accent);">click to browse</strong>
                                </p>
                                <p style="font-size:0.72rem;color:var(--warm-gray);">JPEG, PNG, JPG, GIF — max 2MB each</p>
                            </div>
                        </div>

                        {{-- New photo previews --}}
                        <div id="newPhotoPreviews" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid rgba(0,0,0,0.08);">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ isset($product) ? 'Update Product' : 'Create Product' }}
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        @isset($product)
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="ms-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Delete this product permanently?')">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </form>
                        @endisset
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar tips --}}
    <div class="col-lg-4">
        <div style="background:var(--white);border-radius:6px;border:1px solid rgba(0,0,0,0.07);padding:1.5rem;">
            <h6 style="font-family:var(--font-mono);font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--warm-gray);margin-bottom:1rem;">Photo Tips</h6>
            <ul style="list-style:none;padding:0;margin:0;font-size:0.83rem;color:#555;">
                <li style="padding:0.4rem 0;border-bottom:1px solid rgba(0,0,0,0.05);display:flex;gap:0.5rem;"><i class="bi bi-camera" style="color:var(--accent);"></i> Upload a front/side view first</li>
                <li style="padding:0.4rem 0;border-bottom:1px solid rgba(0,0,0,0.05);display:flex;gap:0.5rem;"><i class="bi bi-arrows-angle-expand" style="color:var(--accent);"></i> Include top, sole, and heel views</li>
                <li style="padding:0.4rem 0;border-bottom:1px solid rgba(0,0,0,0.05);display:flex;gap:0.5rem;"><i class="bi bi-aspect-ratio" style="color:var(--accent);"></i> Square photos look best</li>
                <li style="padding:0.4rem 0;display:flex;gap:0.5rem;"><i class="bi bi-file-image" style="color:var(--accent);"></i> Max 2MB per photo</li>
            </ul>

            @isset($product)
            <hr style="border-color:rgba(0,0,0,0.08);margin:1rem 0;">
            <h6 style="font-family:var(--font-mono);font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--warm-gray);margin-bottom:0.75rem;">Quick Info</h6>
            <div style="font-size:0.8rem;color:var(--warm-gray);">
                <div>Created: {{ $product->created_at->format('M d, Y') }}</div>
                <div>Updated: {{ $product->updated_at->format('M d, Y') }}</div>
                <div>Reviews: {{ $product->reviews_count ?? 0 }}</div>
            </div>
            <a href="{{ route('products.show', $product->id) }}" target="_blank"
               style="display:block;margin-top:0.75rem;font-size:0.78rem;color:var(--accent);text-decoration:none;">
                <i class="bi bi-box-arrow-up-right me-1"></i>View on storefront
            </a>
            @endisset
        </div>
    </div>

</div>

{{-- Hidden inputs for photos to delete --}}
<div id="photosToDelete"></div>

@endsection

@section('scripts')
<script>
    function previewPhotos(input) {
        const container = document.getElementById('newPhotoPreviews');
        const files = Array.from(input.files);
        container.innerHTML = '';

        files.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.cssText = 'position:relative;width:80px;height:80px;border-radius:4px;overflow:hidden;border:1.5px solid rgba(200,169,110,0.4);';
                div.innerHTML = `
                    <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">
                    <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.5);color:white;font-size:0.55rem;text-align:center;padding:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${file.name}</div>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        if (files.length > 0) {
            document.getElementById('dropPlaceholder').innerHTML = `
                <i class="bi bi-check-circle" style="font-size:1.5rem;color:green;"></i>
                <p style="font-size:0.83rem;color:#555;margin-top:0.5rem;">${files.length} photo${files.length > 1 ? 's' : ''} selected</p>
            `;
        }
    }

    function handleDrop(e) {
        e.preventDefault();
        const input = document.getElementById('photoInput');
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        input.files = dt.files;
        previewPhotos(input);
        document.getElementById('dropzone').style.borderColor = 'rgba(200,169,110,0.4)';
        document.getElementById('dropzone').style.background = 'rgba(200,169,110,0.03)';
    }

    function removeExistingPhoto(photoId, btn) {
        if (!confirm('Remove this photo?')) return;
        // Add hidden input to mark for deletion
        const container = document.getElementById('photosToDelete');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_photos[]';
        input.value = photoId;
        container.appendChild(input);
        // Remove the photo preview
        document.getElementById('photo-' + photoId).remove();
    }
</script>
@endsection
