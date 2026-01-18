@extends('adminlte::page')

@section('title', 'Product Details')

@section('content_header')
    <h1>Manage Product Details</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Details List</h3>
                    <div class="card-tools">
                        <a href="{{ route('product-details.create') }}" class="btn btn-primary">Add New Product Detail</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Size ID</th>
                                <th>Color ID</th>
                                <th>Stock</th>
                                <th>Barcode</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productDetails as $productDetail)
                            <tr>
                                <td>{{ $productDetail->id }}</td>
                                <td>{{ $productDetail->product->name }}</td>
                                <td>{{ $productDetail->size_id }}</td>
                                <td>{{ $productDetail->color_id }}</td>
                                <td>
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
                                </td>
                                <td>{{ $productDetail->barcode }}</td>
                                <td>
                                    <span class="badge
                                        @if($productDetail->status)
                                            bg-success
                                        @else
                                            bg-secondary
                                        @endif">
                                        {{ $productDetail->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $productDetail->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('product-details.edit', $productDetail->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('product-details.destroy', $productDetail->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product detail?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No product details found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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