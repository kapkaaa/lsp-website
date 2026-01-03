@extends('adminlte::page')

@section('title', 'View Product Detail')

@section('content_header')
    <h1>View Product Detail</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Detail Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('product-details.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                        <a href="{{ route('product-details.edit', $productDetail->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->id }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Product:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->product->name }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Size ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->size_id }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Color ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->color_id }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Stock:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge
                                @if($productDetail->stock == 0)
                                    bg-danger
                                @elseif($productDetail->stock < 5)
                                    bg-warning
                                @else
                                    bg-success
                                @endif">
                                {{ $productDetail->stock }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Barcode:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->barcode }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge
                                @if($productDetail->status)
                                    bg-success
                                @else
                                    bg-secondary
                                @endif">
                                {{ $productDetail->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->created_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Updated At:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productDetail->updated_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                
                @if($productDetail->productPhotos->count() > 0)
                <div class="card-header">
                    <h3 class="card-title">Product Photos</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($productDetail->productPhotos as $photo)
                            <div class="col-md-3 mb-3">
                                <img src="{{ $photo->photo_url }}" alt="Product Photo" class="img-thumbnail" style="width: 100%; height: auto;">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
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