@extends('adminlte::page')

@section('title', 'View Product Photo')

@section('content_header')
    <h1>View Product Photo</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Photo Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('product-photos.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                        <a href="{{ route('product-photos.edit', $productPhoto->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productPhoto->id }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Product Detail ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productPhoto->productDetail->id ?? 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Photo:</strong>
                        </div>
                        <div class="col-sm-8">
                            <img src="{{ $productPhoto->photo_url }}" alt="Product Photo" class="img-thumbnail" style="max-width: 300px;">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Photo URL:</strong>
                        </div>
                        <div class="col-sm-8">
                            <a href="{{ $productPhoto->photo_url }}" target="_blank">{{ $productPhoto->photo_url }}</a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productPhoto->created_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Updated At:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $productPhoto->updated_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
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