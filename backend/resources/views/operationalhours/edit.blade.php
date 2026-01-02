@extends('adminlte::page')

@section('title', 'Edit Operational Hour')

@section('content_header')
    <h1>Edit Operational Hour</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Operational Hour</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('operational-hours.update', $operationalHour->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="day">Day</label>
                            <select class="form-control @error('day') is-invalid @enderror" id="day" name="day">
                                <option value="">Select Day</option>
                                <option value="Monday" {{ old('day', $operationalHour->day) == 'Monday' ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ old('day', $operationalHour->day) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ old('day', $operationalHour->day) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ old('day', $operationalHour->day) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ old('day', $operationalHour->day) == 'Friday' ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ old('day', $operationalHour->day) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="Sunday" {{ old('day', $operationalHour->day) == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                            @error('day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="opening_time">Opening Time</label>
                                    <input type="time" class="form-control @error('opening_time') is-invalid @enderror" id="opening_time" name="opening_time" value="{{ old('opening_time', $operationalHour->opening_time) }}">
                                    @error('opening_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="closing_time">Closing Time</label>
                                    <input type="time" class="form-control @error('closing_time') is-invalid @enderror" id="closing_time" name="closing_time" value="{{ old('closing_time', $operationalHour->closing_time) }}">
                                    @error('closing_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="open" {{ old('status', $operationalHour->status) == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status', $operationalHour->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Operational Hour</button>
                        <a href="{{ route('operational-hours.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
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