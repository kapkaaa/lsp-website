@extends('adminlte::page')

@section('title', 'Edit Product Detail')

@section('content_header')
    <h1>Edit Product Detail</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Product Detail</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('product-details.update', $productDetail->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="product_id">Product</label>
                            <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $productDetail->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="size_id">Size ID</label>
                            <input type="number" name="size_id" class="form-control @error('size_id') is-invalid @enderror" 
                                   id="size_id" placeholder="Enter Size ID" value="{{ old('size_id', $productDetail->size_id) }}" required>
                            @error('size_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="color_id">Color ID</label>
                            <input type="number" name="color_id" class="form-control @error('color_id') is-invalid @enderror" 
                                   id="color_id" placeholder="Enter Color ID" value="{{ old('color_id', $productDetail->color_id) }}" required>
                            @error('color_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   id="stock" placeholder="Enter Stock" value="{{ old('stock', $productDetail->stock) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="barcode">Barcode</label>
                            <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                   id="barcode" placeholder="Enter Barcode" value="{{ old('barcode', $productDetail->barcode) }}" required>
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="status" class="form-check-input @error('status') is-invalid @enderror"
                                       id="status" value="1" {{ old('status', $productDetail->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Product Detail</button>
                        <a href="{{ route('product-details.index') }}" class="btn btn-default">Cancel</a>
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