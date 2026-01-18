@extends('adminlte::page')

@section('title', 'Edit Product Photo')

@section('content_header')
    <h1>Edit Product Photo</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Product Photo</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('product-photos.update', $productPhoto->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="product_detail_id">Product Detail</label>
                            <select name="product_detail_id" id="product_detail_id" class="form-control @error('product_detail_id') is-invalid @enderror" required>
                                <option value="">Select Product Detail</option>
                                @foreach($productDetails as $productDetail)
                                    <option value="{{ $productDetail->id }}" {{ old('product_detail_id', $productPhoto->product_detail_id) == $productDetail->id ? 'selected' : '' }}>
                                        Product: {{ $productDetail->product->name ?? 'N/A' }} | Size: {{ $productDetail->size_id }} | Color: {{ $productDetail->color_id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_detail_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="photo_url">Photo URL</label>
                            <input type="url" name="photo_url" class="form-control @error('photo_url') is-invalid @enderror" 
                                   id="photo_url" placeholder="Enter Photo URL" value="{{ old('photo_url', $productPhoto->photo_url) }}" required>
                            @error('photo_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Current Photo</label><br>
                            <img src="{{ $productPhoto->photo_url }}" alt="Current Photo" width="200" class="img-thumbnail">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Product Photo</button>
                        <a href="{{ route('product-photos.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I am here") </script>
@stop