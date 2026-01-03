@extends('adminlte::page')

@section('title', 'View User')

@section('content_header')
    <h1>View User</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->id }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Role ID:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->role_id }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Name:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->name }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Username:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->username }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>NIK:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->nik }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Address:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->address }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>City:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->city }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Phone:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->phone }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge 
                                @if($user->status)
                                    bg-success
                                @else
                                    bg-secondary
                                @endif">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Profile Photo:</strong>
                        </div>
                        <div class="col-sm-8">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile Photo" class="img-thumbnail" width="150">
                            @else
                                No photo
                            @endif
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->created_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Updated At:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->updated_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
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