@extends('layouts.admin')

@section('page_title', 'Edit Brand')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <!-- Form edit brand -->
            <div class="card">
            <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Edit Brand Information</h3>
                </div>
                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $brand->name) }}" 
                                   placeholder="Enter brand name" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="information">Information</label>
                            <textarea class="form-control @error('information') is-invalid @enderror" 
                                      id="information" 
                                      name="information" 
                                      rows="3" 
                                      placeholder="Enter brand information">{{ old('information', $brand->information) }}</textarea>
                            @error('information')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom kanan: informasi tambahan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Brand Statistics</h3>
                </div>
                <div class="card-body">
                    <p><strong>Products:</strong> {{ $brand->products()->count() }}</p>
                    <p><strong>Created:</strong> {{ $brand->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $brand->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> All Brands
                    </a>
                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" id="delete-form-{{ $brand->id }}">
                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            class="btn btn-danger btn-block mt-2"
                            onclick="confirmDelete('delete-form-{{ $brand->id }}')">
                            <i class="fas fa-trash"></i> Delete Brand
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection