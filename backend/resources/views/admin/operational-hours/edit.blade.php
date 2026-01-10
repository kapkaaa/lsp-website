@extends('layouts.admin')

@section('page_title', 'Edit Operational Hour')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.operational-hours.index') }}">Operational Hours</a></li>
    <li class="breadcrumb-item active">Edit {{ $operationalHour->day }}</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Operational Hour - {{ $operationalHour->day }}
                    </h3>
                </div>
                <form action="{{ route('admin.operational-hours.update', $operationalHour->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Day</label>
                            <input type="text" class="form-control" value="{{ $operationalHour->day }}" readonly>
                            <small class="text-muted">Day cannot be changed</small>
                        </div>

                        <div class="form-group">
                            <label>Service Type</label>
                            <input type="text" class="form-control" value="{{ ucfirst($operationalHour->service_type) }}" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Open Time <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control @error('open_time') is-invalid @enderror" 
                                           name="open_time" 
                                           value="{{ old('open_time', \Carbon\Carbon::parse($operationalHour->open_time)->format('H:i')) }}" 
                                           required>
                                    @error('open_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Close Time <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control @error('close_time') is-invalid @enderror" 
                                           name="close_time" 
                                           value="{{ old('close_time', \Carbon\Carbon::parse($operationalHour->close_time)->format('H:i')) }}" 
                                           required>
                                    @error('close_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="open" {{ old('status', $operationalHour->status) == 'open' ? 'selected' : '' }}>
                                    Open
                                </option>
                                <option value="closed" {{ old('status', $operationalHour->status) == 'closed' ? 'selected' : '' }}>
                                    Closed
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Changes will affect online ordering system.
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.operational-hours.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Information
                    </h3>
                </div>
                <div class="card-body">
                    <h5>Operational Hours Rules:</h5>
                    <ul>
                        <li>Online orders can only be placed during operational hours</li>
                        <li>Customer service chat is available only during operational hours</li>
                        <li>Default hours: 10:00 - 17:00</li>
                        <li>You can set different hours for each day</li>
                        <li>Status "Closed" will disable orders for that day</li>
                    </ul>

                    <hr>

                    <h5>Current Day Status:</h5>
                    <table class="table table-sm">
                        <tr>
                            <th>Day:</th>
                            <td>{{ $operationalHour->day }}</td>
                        </tr>
                        <tr>
                            <th>Current Time:</th>
                            <td>{{ now()->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Is Open Now:</th>
                            <td>
                                @if($operationalHour->isCurrentlyOpen())
                                    <span class="badge badge-success">YES</span>
                                @else
                                    <span class="badge badge-danger">NO</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection