@extends('layouts.admin')

@section('page_title', 'Products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('main_content')
<div class="container-fluid">   
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
                    <table id="productsTable" class="table table-hover table-striped">
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
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($product->productDetails->first()?->photos->first())
                                        <img src="{{ $product->productDetails->first()->photos->first()->photo_url }}"
                                             alt="{{ $product->name }}"
                                             class="img-thumbnail-list"
                                             style="width: 50px; height: auto;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->brand?->name ?? '-' }}</td>
                                <td>{{ $product->type?->name ?? '-' }}</td>
                                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $totalStock = $product->getTotalStock();
                                        $availableStock = $product->getAvailableStock();
                                        $badgeClass = match (true) {
                                            $availableStock == 0 => 'badge-danger',
                                            $availableStock < 10 => 'badge-warning',
                                            default => 'badge-success'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
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
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('admin.products.destroy', $product->id) }}"
                                            method="POST" style="display: inline;">
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
                {{-- Pagination dihapus karena DataTables menangani tampilan --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Hancurkan instance sebelumnya jika ada
    if ($.fn.DataTable.isDataTable('#productsTable')) {
        $('#productsTable').DataTable().destroy();
    }

    $('#productsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": [0, 1, 8] }, // Kolom No, Image, Action tidak bisa di-sort
            { "searchable": false, "targets": [0, 1, 5, 6, 7, 8] } // Sesuaikan kolom yang bisa dicari
        ]
    });
});
</script>
@endpush