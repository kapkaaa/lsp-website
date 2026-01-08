@extends('layouts.admin')

@section('page_title', 'Colors')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Colors</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Color List</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.colors.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Color
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="colorsTable" class="table table-bordered table-striped">
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
                            @foreach($colors as $index => $color)
                            <tr>
                                <td>{{ $colors->firstItem() + $index }}</td>
                                <td>{{ $color->name }}</td>
                                <td>{{ $color->information ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $color->products_count }} products</span>
                                </td>
                                <td>{{ $color->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.colors.edit', $color->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-form-{{ $color->id }}"
                                            action="{{ route('admin.colors.destroy', $color->id) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('delete-form-{{ $color->id }}')"
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
                    {{ $colors->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#colorsTable').Destroy();
        $('#colorsTable').DataTable({
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