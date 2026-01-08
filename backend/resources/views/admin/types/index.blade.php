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
                            @foreach($types as $index => $type)
                                <tr>
                                    <td>{{ $types->firstItem() + $index }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->information ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $type->products_count }} products</span>
                                    </td>
                                    <td>{{ $type->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.types.edit', $type->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form id="delete-form-{{ $type->id }}" 
                                                  action="{{ route('admin.types.destroy', $type->id) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete('delete-form-{{ $type->id }}')" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $types->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#typesTable').Destroy();
    $('#typesTable').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
    });
});
</script>
@endpush