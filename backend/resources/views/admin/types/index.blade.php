@extends('layouts.admin')

@section('page_title', 'Types')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Types</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Type List</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.types.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Type
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="typesTable" class="table table-bordered table-striped">
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
                            @forelse($types as $index => $type)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->information ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $type->products_count }} Product</span>
                                    </td>
                                    <td>{{ $type->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.types.edit', $type->id) }}" 
                                               class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"> Detail</i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No types found</td>
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
    if ($.fn.DataTable.isDataTable('#typesTable')) {
        $('#typesTable').DataTable().destroy();
    }

    $('#typesTable').DataTable({
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