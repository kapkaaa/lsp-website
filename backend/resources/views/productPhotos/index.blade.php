@extends('adminlte::page')

@section('title', 'Product Photos')

@section('content_header')
    <h1>Manage Product Photos</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Photos List</h3>
                    <div class="card-tools">
                        <a href="{{ route('product-photos.create') }}" class="btn btn-primary">Add New Product Photo</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Detail</th>
                                <th>Photo</th>
                                <th>Photo URL</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productPhotos as $productPhoto)
                            <tr>
                                <td>{{ $productPhoto->id }}</td>
                                <td>{{ $productPhoto->productDetail->id ?? 'N/A' }}</td>
                                <td>
                                    <img src="{{ $productPhoto->photo_url }}" alt="Product Photo" width="50" height="50" class="img-thumbnail">
                                </td>
                                <td>
                                    <a href="{{ $productPhoto->photo_url }}" target="_blank" class="text-truncate" style="max-width: 200px; display: inline-block;">
                                        {{ $productPhoto->photo_url }}
                                    </a>
                                </td>
                                <td>{{ $productPhoto->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('product-photos.edit', $productPhoto->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('product-photos.destroy', $productPhoto->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product photo?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No product photos found</td>
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