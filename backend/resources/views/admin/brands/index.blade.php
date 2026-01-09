@extends('layouts.admin')

@section('page_title', 'Brands')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Brands</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Brand List</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Brand
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="brandsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Name</th>
                                <th>Information</th>
                                <th>Total Products</th>
                                <th>Created At</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $index => $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->information ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $brand->product_details_count }} variants</span>
                                    </td>
                                    <td>{{ $brand->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form id="delete-form-{{ $brand->id }}" 
                                                  action="{{ route('admin.brands.destroy', $brand->id) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete('delete-form-{{ $brand->id }}')" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No brands found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Laravel dihapus karena DataTables menangani paging --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Hancurkan instance DataTable sebelumnya jika ada
    if ($.fn.DataTable.isDataTable('#brandsTable')) {
        $('#brandsTable').DataTable().destroy();
    }

    $('#brandsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": [0, 5] },   // No & Action tidak bisa di-sort
            { "searchable": false, "targets": [0, 3, 4, 5] } // Kolom tertentu tidak ikut search
        ]
    });
});
</script>
@endpush