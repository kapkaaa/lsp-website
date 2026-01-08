@extends('layouts.admin')

@section('page_title', 'Products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search product..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="brand_id" class="form-control">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="type_id" class="form-control">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product List</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Variants</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $product)
                                <tr>
                                    <td>{{ $products->firstItem() + $index }}</td>
                                    <td>
                                        @if($product->productDetails->first() && $product->productDetails->first()->photos->first())
                                            <img src="{{ $product->productDetails->first()->photos->first()->photo_url }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-thumbnail-list">
                                        @else
                                            <img src="https://via.placeholder.com/50" 
                                                 alt="No Image" 
                                                 class="img-thumbnail-list">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                    </td>
                                    <td>{{ $product->brand->name }}</td>
                                    <td>{{ $product->type->name }}</td>
                                    <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $totalStock = $product->getTotalStock();
                                            $availableStock = $product->getAvailableStock();
                                        @endphp
                                        <span class="badge {{ $availableStock == 0 ? 'badge-danger' : ($availableStock < 10 ? 'badge-warning' : 'badge-success') }}">
                                            {{ $availableStock }} / {{ $totalStock }} pcs
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $product->productDetails->count() }} variants
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.show', $product->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form id="delete-form-{{ $product->id }}" 
                                                  action="{{ route('admin.products.destroy', $product->id) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete('delete-form-{{ $product->id }}')" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No products found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection