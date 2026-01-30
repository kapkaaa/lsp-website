@extends('layouts.admin')

@section('page_title', 'Create Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

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
    overflow-y: auto; /* Allow scrolling within sidebar if too tall */
    max-height: calc(100vh - 80px);
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
        max-height: none;
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
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        <div class="row">
            <div class="col-md-8 main-content-with-fixed-sidebar">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand_id">Brand <span class="text-danger">*</span></label>
                                    <select class="form-control @error('brand_id') is-invalid @enderror" 
                                            id="brand_id" 
                                            name="brand_id" 
                                            required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_id">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type_id') is-invalid @enderror" 
                                            id="type_id" 
                                            name="type_id" 
                                            required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Enter product name" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_price">Cost Price <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('cost_price') is-invalid @enderror" 
                                           id="cost_price" 
                                           name="cost_price" 
                                           value="{{ old('cost_price') }}" 
                                           placeholder="0" 
                                           required>
                                    @error('cost_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selling_price">Selling Price <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('selling_price') is-invalid @enderror" 
                                           id="selling_price" 
                                           name="selling_price" 
                                           value="{{ old('selling_price') }}" 
                                           placeholder="0" 
                                           required>
                                    @error('selling_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Variants</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="addVariant">
                                <i class="fas fa-plus"></i> Add Variant
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="variantsContainer">
                            <!-- Variants will be added here -->
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </form>
</div>

<!-- SIDEBAR FIXED OUTSIDE CONTAINER -->
<div class="sidebar-fixed">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Actions</h3>
        </div>
        <div class="card-body">
            <button type="submit" form="productForm" class="btn btn-primary btn-block">
                <i class="fas fa-save"></i> Save Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">Quick Reference</h3>
        </div>
        <div class="card-body">
            <p class="text-sm"><strong>Available Sizes:</strong></p>
            <div class="mb-2">
                @foreach($sizes as $size)
                    <span class="badge badge-secondary">{{ $size->name }}</span>
                @endforeach
            </div>
            <p class="text-sm"><strong>Available Colors:</strong></p>
            <div>
                @foreach($colors as $color)
                    <span class="badge badge-info">{{ $color->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
    </form>
</div>

<!-- Variant Template -->
<template id="variantTemplate">
    <div class="variant-item card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Size <span class="text-danger">*</span></label>
                        <select class="form-control" name="variants[INDEX][size_id]" required>
                            <option value="">Select Size</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Color <span class="text-danger">*</span></label>
                        <select class="form-control" name="variants[INDEX][color_id]" required>
                            <option value="">Select Color</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="variants[INDEX][stock]" min="0" value="0" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Photos</label>
                        <div class="d-flex align-items-center">
                            <!-- Hidden actual input -->
                            <input type="file" class="d-none variant-photos-input" name="variants[INDEX][photos][]" multiple accept="image/*">
                            
                            <!-- Manager Trigger -->
                            <button type="button" class="btn btn-outline-primary btn-sm manage-photos">
                                <i class="fas fa-images"></i> Tambah Image <span class="badge badge-primary photo-count ml-1">0</span>
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block">Click to manage photos</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="button" class="btn btn-danger btn-sm remove-variant">
                        <i class="fas fa-trash"></i> Remove Variant
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

<!-- Advanced Image Manager Modal -->
<div class="modal fade" id="imageManagerModal" tabindex="-1" role="dialog" aria-labelledby="imageManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageManagerModalLabel">Manage Variant Photos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <!-- Toolbar -->
                <div class="mb-3">
                    <button type="button" class="btn btn-success" id="btnAddNewPhoto">
                        <i class="fas fa-plus"></i> Add Photos
                    </button>
                    <input type="file" id="tempPhotoInput" multiple accept="image/*" class="d-none">
                    <span class="text-muted ml-2">Selected: <span id="managerPhotoCount">0</span></span>
                </div>

                <!-- Grid -->
                <div id="managerPhotoGrid" class="d-flex flex-wrap border rounded p-3 bg-white" style="min-height: 200px; align-content: flex-start;">
                    <!-- Photos will be injected here -->
                    <div class="w-100 text-center text-muted align-self-center empty-state">
                        No photos selected
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSavePhotos">Done</button>
            </div>
        </div>
    </div>
</div>


@push('js')
<script>
$(document).ready(function() {
    // Hindari double execution
    if (window.productCreateInitialized) return;
    window.productCreateInitialized = true;

    let variantIndex = 0;

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

    // Bind click once
    $('#addVariant').off('click').on('click', addVariant);

    // Remove variant
    $(document).off('click', '.remove-variant').on('click', '.remove-variant', function() {
        $(this).closest('.variant-item').remove();
    });

    // Add first variant
    addVariant();

    // Form validation
    $('#productForm').off('submit').on('submit', function(e) {
        if ($('.variant-item').length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No Variants',
                text: 'Please add at least one product variant'
            });
            return false;
        }
    });

    // Advanced Image Manager Logic
    let currentManagerTargetInput = null; // The hidden input we are editing
    let managerFiles = []; // Array to hold File objects

    // Open Manager
    $(document).on('click', '.manage-photos', function() {
        const formGroup = $(this).closest('.form-group');
        currentManagerTargetInput = formGroup.find('.variant-photos-input');
        
        // Load existing files
        managerFiles = Array.from(currentManagerTargetInput[0].files || []);
        
        renderManagerGrid();
        $('#imageManagerModal').modal('show');
    });

    // Add Photos Button
    $('#btnAddNewPhoto').on('click', function() {
        $('#tempPhotoInput').click();
    });

    // Handle File Selection
    $('#tempPhotoInput').on('change', function() {
        if (this.files && this.files.length > 0) {
            Array.from(this.files).forEach(file => {
                managerFiles.push(file);
            });
            renderManagerGrid();
        }
        // Reset temp input so same files can be selected again if needed
        $(this).val('');
    });

    // Render Grid
    function renderManagerGrid() {
        const grid = $('#managerPhotoGrid');
        const countSpan = $('#managerPhotoCount');
        
        grid.empty();
        countSpan.text(managerFiles.length);

        if (managerFiles.length === 0) {
            grid.append('<div class="w-100 text-center text-muted align-self-center empty-state">No photos selected</div>');
            return;
        }

        managerFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const item = $(`
                    <div class="position-relative m-2 shadow-sm border rounded" style="width: 100px; height: 100px;">
                        <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover; border-radius: 4px;">
                        <button type="button" class="btn btn-danger btn-xs position-absolute d-flex align-items-center justify-content-center" 
                                style="top: -8px; right: -8px; width: 20px; height: 20px; border-radius: 50%; padding: 0;"
                                data-index="${index}">
                            &times;
                        </button>
                    </div>
                `);
                
                // Bind delete event specifically to this closure to capture correct index at render time? 
                // Better to use data-index and delegate
                grid.append(item);
            }
            reader.readAsDataURL(file);
        });
    }

    // Remove Photo from Manager
    $(document).on('click', '#managerPhotoGrid button', function() {
        const index = $(this).data('index');
        managerFiles.splice(index, 1);
        renderManagerGrid();
    });

    // Save Changes
    $('#btnSavePhotos').on('click', function() {
        if (!currentManagerTargetInput) return;

        // Use DataTransfer to reconstruct FileList
        const dataTransfer = new DataTransfer();
        managerFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        // Update target input
        currentManagerTargetInput[0].files = dataTransfer.files;

        // Update UI Badge
        const formGroup = currentManagerTargetInput.closest('.form-group');
        formGroup.find('.photo-count').text(managerFiles.length);

        $('#imageManagerModal').modal('hide');
    });
});
</script>
@endpush