@extends('layouts.admin')

@section('page_title', 'Edit Size')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.sizes.index') }}">Sizes</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 main-content-with-fixed-sidebar">
            <!-- Form edit size -->
            <div class="card">
            <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Edit Size Information</h3>
                </div>
                <form action="{{ route('admin.sizes.update', $size->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Size Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $size->name) }}" 
                                   placeholder="Enter size name" 
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
                                      placeholder="Enter size information">{{ old('information', $size->information) }}</textarea>
                            @error('information')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom kanan: informasi tambahan -->
        <div class="col-md-6 fixed-sidebar">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Size Statistics</h3>
                </div>
                <div class="card-body">
                    <p><strong>Variant:</strong> {{ $size->productDetails()->count() }}</p>
                    <p><strong>Created:</strong> {{ $size->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $size->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> All Sizes
                    </a>
                    <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" id="delete-form-{{ $size->id }}">
                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            class="btn btn-danger btn-block mt-2"
                            onclick="confirmDelete('delete-form-{{ $size->id }}')">
                            <i class="fas fa-trash"></i> Delete Size
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection