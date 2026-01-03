@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1>Manage Products</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products List</h3>
                    <div class="card-tools">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand->name }}</td>
                                <td>{{ $product->type->name }}</td>
                                <td>Rp {{ number_format($product->cost_price, 2) }}</td>
                                <td>Rp {{ number_format($product->selling_price, 2) }}</td>
                                <td>{{ $product->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('product-details.index') }}?product_id={{ $product->id }}" class="btn btn-sm btn-info">Details</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No products found</td>
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