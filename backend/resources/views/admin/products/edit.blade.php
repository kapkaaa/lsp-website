@extends('layouts.admin')

@section('page_title', 'Edit Product')

@push('css')
<style>
/* Sidebar fixed di kanan, full height */
.sidebar-fixed {
    position: fixed;
    top: 70px; /* Sesuaikan dengan tinggi navbar */
    right: 15px;
    width: 25%;
    max-width: 350px;
    min-height: 100vh;
    z-index: 1000;
    background: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* Beri ruang ke main content agar tidak tertutup sidebar */
.main-content-with-fixed-sidebar {
    margin-right: 26%;
    padding-right: 20px;
}

/* Responsif: nonaktifkan fixed di mobile */
@media (max-width: 991.98px) {
    .sidebar-fixed {
        position: static;
        width: auto;
        margin-top: 20px;
        right: auto;
        min-height: auto;
        box-shadow: none;
    }
    .main-content-with-fixed-sidebar {
        margin-right: 0;
        padding-right: 0;
    }
}
</style>
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <!-- MAIN FORM -->
        <div class="col-md-8 main-content-with-fixed-sidebar">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Edit Product Information</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method('PUT')

                        <!-- Brand -->
                        <div class="form-group">
                            <label for="brand_id"><strong>Brand *</strong></label>
                            <select class="form-control @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ (old('brand_id') ?? $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="form-group">
                            <label for="type_id"><strong>Type *</strong></label>
                            <select class="form-control @error('type_id') is-invalid @enderror" id="type_id" name="type_id" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ (old('type_id') ?? $product->type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('type_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name"><strong>Product Name *</strong></label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $product->name) }}"
                                placeholder="Enter product name"
                                required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Prices -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_price"><strong>Cost Price *</strong></label>
                                    <input type="number"
                                        class="form-control @error('cost_price') is-invalid @enderror"
                                        id="cost_price"
                                        name="cost_price"
                                        value="{{ old('cost_price', $product->cost_price) }}"
                                        placeholder="0"
                                        required>
                                    @error('cost_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selling_price"><strong>Selling Price *</strong></label>
                                    <input type="number"
                                        class="form-control @error('selling_price') is-invalid @enderror"
                                        id="selling_price"
                                        name="selling_price"
                                        value="{{ old('selling_price', $product->selling_price) }}"
                                        placeholder="0"
                                        required>
                                    @error('selling_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Variants Section -->
                        <div class="mt-4">
                            <h5><strong>Product Variants</strong></h5>
                            <div id="variantsContainer">
                                @if($product->productDetails->count() > 0)
                                @foreach($product->productDetails as $index => $variant)
                                <div class="variant-item mb-3 p-3 border rounded bg-white">
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Size *</label>
                                            <select class="form-control" name="variants[{{ $index }}][size_id]" required>
                                                <option value="">Select Size</option>
                                                @foreach($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                                    {{ $size->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Color *</label>
                                            <select class="form-control" name="variants[{{ $index }}][color_id]" required>
                                                <option value="">Select Color</option>
                                                @foreach($colors as $color)
                                                <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                    {{ $color->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Stock *</label>
                                            <input type="number" class="form-control" name="variants[{{ $index }}][stock]" min="0" value="{{ old("variants.{$index}.stock", $variant->stock) }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Photos</label>
                                            <!-- Existing Photos -->
                                            @if($variant->photos->count() > 0)
                                            <div class="d-flex flex-wrap mb-2">
                                                @foreach($variant->photos as $photo)
                                                <div class="position-relative mr-2 mb-2 existing-photo-item" id="photo-{{ $photo->id }}">
                                                    <img src="{{ $photo->photo_url }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                         onclick="showImagePreview('{{ $photo->photo_url }}')"
                                                         alt="Variant Photo">
                                                    <button type="button" 
                                                            class="btn btn-xs btn-danger position-absolute" 
                                                            style="top: -5px; right: -5px; border-radius: 50%; padding: 0 4px;"
                                                            onclick="markPhotoForDeletion({{ $photo->id }})">
                                                        &times;
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            
                                            <input type="file" class="form-control-file" name="variants[{{ $index }}][photos][]" multiple accept="image/*">
                                            <small class="text-muted d-block">Upload new photos to add.</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-variant">
                                        <i class="fas fa-trash"></i> Remove Variant
                                    </button>
                                </div>
                                @endforeach
                                @php $variantIndex = $product->productDetails->count(); @endphp
                                @else
                                @php $variantIndex = 0; @endphp
                                @endif
                            </div>

                            <button type="button" class="btn btn-sm btn-primary mt-2" id="addVariant">
                                <i class="fas fa-plus"></i> Add Variant
                            </button>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SIDEBAR FIXED OUTSIDE CONTAINER -->
<div class="sidebar-fixed">
    <!-- Product Statistics -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Product Statistics</h5>
        </div>
        <div class="card-body">
            <p><strong>Created:</strong> {{ $product->created_at->format('d M Y H:i') }}</p>
            <p><strong>Last Updated:</strong> {{ $product->updated_at->format('d M Y H:i') }}</p>
            <p><strong>Total Variants:</strong> {{ $product->productDetails->count() }}</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Actions</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block mb-2">
                <i class="fas fa-list"></i> All Products
            </a>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="delete-form-{{ $product->id }}">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    class="btn btn-danger btn-block"
                    onclick="confirmDelete('delete-form-{{ $product->id }}')">
                    <i class="fas fa-trash"></i> Delete Product
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Variant Template -->
<template id="variantTemplate">
    <div class="variant-item mb-3 p-3 border rounded bg-white">
        <div class="row">
            <div class="col-md-3">
                <label>Size *</label>
                <select class="form-control" name="variants[INDEX][size_id]" required>
                    <option value="">Select Size</option>
                    @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Color *</label>
                <select class="form-control" name="variants[INDEX][color_id]" required>
                    <option value="">Select Color</option>
                    @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Stock *</label>
                <input type="number" class="form-control" name="variants[INDEX][stock]" min="0" value="0" required>
            </div>
            <div class="col-md-3">
                <label>Photos</label>
                <input type="file" class="form-control-file" name="variants[INDEX][photos][]" multiple accept="image/*">
                <small class="text-muted d-block">Max 5 photos</small>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-variant">
            <i class="fas fa-trash"></i> Remove Variant
        </button>
    </div>
</template>
@endsection

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center bg-dark">
                <img src="" id="previewImage" class="img-fluid" style="max-height: 80vh;" alt="Preview">
            </div>
        </div>
    </div>
</div>


@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        if (window.productCreateInitialized) return;
        window.productCreateInitialized = true;

        function getMaxVariantIndex() {
            let maxIndex = -1;
            $('.variant-item').each(function() {
                const inputs = $(this).find('input, select');
                inputs.each(function() {
                    const name = $(this).attr('name');
                    if (name && name.includes('variants[')) {
                        const match = name.match(/variants\[(\d+)\]/);
                        if (match) {
                            const index = parseInt(match[1]);
                            if (index > maxIndex) maxIndex = index;
                        }
                    }
                });
            });
            return maxIndex;
        }

        let variantIndex = getMaxVariantIndex() + 1;

        function addVariant() {
            let template = $('#variantTemplate').html();
            if (!template) {
                console.error('Variant template not found');
                return;
            }
            template = template.replace(/INDEX/g, variantIndex);
            $('#variantsContainer').append(template);
            variantIndex++;
        }

        $('#addVariant').off('click').on('click', addVariant);

        $(document).off('click', '.remove-variant').on('click', '.remove-variant', function() {
            $(this).closest('.variant-item').remove();
            variantIndex = getMaxVariantIndex() + 1;
        });

        $('#productForm').on('submit', function(e) {
            if ($('.variant-item').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Variants',
                    text: 'Please add at least one product variant'
                });
            }
        });
    });

    function confirmDelete(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    // Image Preview Global Function
    window.showImagePreview = function(url) {
        $('#previewImage').attr('src', url);
        $('#imagePreviewModal').modal('show');
    }

    // Mark for Deletion Global Function
    window.markPhotoForDeletion = function(photoId) {
        Swal.fire({
            title: 'Delete this photo?',
            text: "It will be removed upon saving changes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove the UI element
                $(`#photo-${photoId}`).remove();
                
                // Add hidden input to the form
                const input = `<input type="hidden" name="delete_photo_ids[]" value="${photoId}">`;
                $('#productForm').append(input);
                
                // Optional: Show temporary success message
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Photo marked for deletion',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }
</script>
@endpush