@extends('layouts.admin')

@section('page_title', 'Create Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
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

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Save Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
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
                        <input type="file" class="form-control-file" name="variants[INDEX][photos][]" multiple accept="image/*">
                        <small class="text-muted">Max 5 photos</small>
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

@push('js')
<script>
$(document).ready(function() {
    let variantIndex = 0;

    // Add variant
    $('#addVariant').click(function() {
        let template = $('#variantTemplate').html();
        template = template.replace(/INDEX/g, variantIndex);
        $('#variantsContainer').append(template);
        variantIndex++;
    });

    // Remove variant
    $(document).on('click', '.remove-variant', function() {
        $(this).closest('.variant-item').remove();
    });

    // Add first variant by default
    $('#addVariant').click();

    // Form validation
    $('#productForm').submit(function(e) {
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
});
</script>
@endpush