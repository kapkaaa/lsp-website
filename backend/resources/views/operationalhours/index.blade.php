@extends('adminlte::page')

@section('title', 'Operational Hours')

@section('content_header')
    <h1>Manage Operational Hours</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Operational Hours</h3>
                    <div class="card-tools">
                        <a href="{{ route('operational-hours.create') }}" class="btn btn-primary">Add New Operational Hour</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Day</th>
                                <th>Opening Time</th>
                                <th>Closing Time</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operationalHours as $hour)
                            <tr>
                                <td>{{ $hour->id }}</td>
                                <td>{{ $hour->day }}</td>
                                <td>{{ $hour->opening_time }}</td>
                                <td>{{ $hour->closing_time }}</td>
                                <td>
                                    <span class="badge 
                                        @if($hour->status == 'open') 
                                            bg-success 
                                        @else 
                                            bg-danger 
                                        @endif">
                                        {{ ucfirst($hour->status) }}
                                    </span>
                                </td>
                                <td>{{ $hour->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('operational-hours.edit', $hour->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('operational-hours.destroy', $hour->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this operational hour?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No operational hours found</td>
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