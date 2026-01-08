@extends('layouts.admin')

@section('page_title', 'Edit Color')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <!-- Form edit color -->
            <div class="card">
            <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Edit Color Information</h3>
                </div>
                <form action="{{ route('admin.colors.update', $color->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Color Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $color->name) }}" 
                                   placeholder="Enter color name" 
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
                                      placeholder="Enter color information">{{ old('information', $color->information) }}</textarea>
                            @error('information')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Color Statistics</h3>
                </div>
                <div class="card-body">
                    <p><strong>Products:</strong> {{ $color->productDetails()->count() }}</p>
                    <p><strong>Created:</strong> {{ $color->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $color->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> All Colors
                    </a>
                    <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this color?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block mt-2">
                            <i class="fas fa-trash"></i> Delete Color
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection