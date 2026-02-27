@extends('layouts.admin')

@section('page-title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="admin-form-card">
            <div class="card-head">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i>
                    {{ isset($product) ? 'Edit Product' : 'Create New Product' }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" 
                      action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name ?? '') }}" 
                                   required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       name="price" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $product->price ?? '') }}" 
                                       step="0.01" 
                                       min="0" 
                                       required>
                            </div>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach ($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand *</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">Select Brand</option>
                                @foreach ($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}" 
                                            {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" 
                                  required>{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Product Photos *</label>
                        <div id="photoUploadArea" class="border-2 border-dashed rounded p-4 text-center bg-light">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-2">Drag & drop photos here or click to browse</p>
                            <input type="file" 
                                   name="photos[]" 
                                   id="photoInput" 
                                   class="d-none" 
                                   multiple 
                                   accept="image/*"
                                   @if(!isset($product)) required>
                            <button type="button" 
                                    class="btn btn-outline-primary" 
                                    onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-folder-open me-2"></i>Choose Photos
                            </button>
                            <small class="text-muted d-block mt-2">
                                Upload multiple photos (JPG, PNG, GIF • Max 2MB each)
                            </small>
                        </div>
                        
                        <!-- Photo Preview Area -->
                        <div id="photoPreview" class="row mt-3">
                            @if(isset($product) && $product->photos)
                                @foreach ($product->photos as $photo)
                                    <div class="col-md-3 mb-3 photo-item">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $photo->image_path) }}" 
                                                 alt="Product photo" 
                                                 class="img-fluid rounded" 
                                                 style="height: 120px; width: 100%; object-fit: cover;">
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                    onclick="removePhoto(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        @error('photos')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($product) ? 'Update Product' : 'Create Product' }}
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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
                    <i class="bi bi-info-circle me-2"></i>Product Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-semibold">Photo Requirements:</h6>
                    <ul class="small text-muted">
                        <li>Minimum 1 photo required</li>
                        <li>Maximum 10 photos allowed</li>
                        <li>File types: JPG, PNG, GIF</li>
                        <li>Maximum file size: 2MB per photo</li>
                        <li>Recommended size: 800x800px</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-semibold">Best Practices:</h6>
                    <ul class="small text-muted">
                        <li>Use clear, high-quality images</li>
                        <li>Show product from multiple angles</li>
                        <li>Include lifestyle photos if possible</li>
                        <li>Ensure good lighting</li>
                        <li>Use consistent background</li>
                    </ul>
                </div>
                
                @if(isset($product))
                <div class="alert alert-info">
                    <h6 class="fw-semibold mb-2">Current Product:</h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted" style="width: 80px;">ID:</td>
                            <td class="font-mono">#{{ $product->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created:</td>
                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Updated:</td>
                            <td>{{ $product->updated_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Photos:</td>
                            <td>{{ $product->photos->count() }} uploaded</td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Drag and drop functionality
const photoUploadArea = document.getElementById('photoUploadArea');
const photoInput = document.getElementById('photoInput');
const photoPreview = document.getElementById('photoPreview');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    photoUploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Handle dropped files
photoUploadArea.addEventListener('drop', handleDrop);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
}

// Handle selected files
photoInput.addEventListener('change', function(e) {
    handleFiles(e.target.files);
});

function handleFiles(files) {
    ([...files]).forEach(uploadFile);
}

function uploadFile(file) {
    if (!file.type.startsWith('image/')) {
        alert('Please upload only image files.');
        return;
    }
    
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB.');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        addPhotoPreview(e.target.result);
    };
    reader.readAsDataURL(file);
}

function addPhotoPreview(src) {
    const photoItem = document.createElement('div');
    photoItem.className = 'col-md-3 mb-3 photo-item';
    
    photoItem.innerHTML = `
        <div class="position-relative">
            <img src="${src}" alt="Product photo" class="img-fluid rounded" style="height: 120px; width: 100%; object-fit: cover;">
            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removePhoto(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    photoPreview.appendChild(photoItem);
}

function removePhoto(button) {
    button.closest('.photo-item').remove();
}

// Make photoUploadArea clickable
photoUploadArea.addEventListener('click', function() {
    photoInput.click();
});
</script>
@endsection
