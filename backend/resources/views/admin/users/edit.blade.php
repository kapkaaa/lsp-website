@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
<h1>Edit User</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form Edit User -->
        <div class="col-md-8 main-content-with-fixed-sidebar">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Edit User Information</h3>
                </div>
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_id">Role <span class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id"
                                        class="form-control @error('role_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Role --</option>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}" placeholder="Enter Name" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $user->username) }}" placeholder="Enter Username" required>
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter New Password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Confirm New Password">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nik">NIK <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                                        value="{{ old('nik', $user->nik) }}" placeholder="Enter NIK" required>
                                    @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                        rows="3" placeholder="Enter Address" required>{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', $user->city) }}" placeholder="Enter City" required>
                                    @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}" placeholder="Enter Phone" required>
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-warning ml-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Statistics & Actions -->
        <div class="col-md-4 fixed-sidebar">
            <!-- User Statistics -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">User Statistics</h3>
                </div>
                <div class="card-body">
                    <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Updated At:</strong> {{$user->updated_at->format('d M Y H:i') }}
                    </p>
                    @if ($user->role->name === 'Customer')
                    <p><strong>Total Orders:</strong> {{ $user->orders()->count() }}</p>
                    @endif

                    <p><strong>Role:</strong> {{ $user->role->name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge {{ $user->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-list"></i> All Users
                    </a>

                    <!-- Tombol Hapus -->
                    @if($user->id != auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}">
                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            class="btn btn-danger btn-block mt-2"
                            onclick="confirmDelete('delete-form-{{ $user->id }}')">
                            <i class="fas fa-trash"></i> Delete User
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
{{-- Tambahkan CSS jika perlu --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
</script>
@stop