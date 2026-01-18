@extends('layouts.admin')

@section('page_title', 'Operational Hours')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Operational Hours</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i> Operational Hours Settings
                    </h3>
                    <div class="card-tools d-flex align-items-center">
                        <!-- Filter Service Type (tanpa refresh) -->
                        <div class="mr-2">
                            <select id="serviceTypeFilter" class="form-control form-control-sm">
                                <option value="">All Service Types</option>
                                <option value="Store">Store</option>
                                <option value="website">Website</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bulkUpdateModal">
                            <i class="fas fa-edit"></i> Bulk Update
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="operationalHoursTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Day</th>
                                <th>Service Type</th>
                                <th>Open Time</th>
                                <th>Close Time</th>
                                <th>Status</th>
                                <th>Currently</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($operationalHours as $index => $hour)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $hour->day }}</strong></td>
                                <td data-service-type="{{ $hour->service_type }}">
                                    @if($hour->service_type == 'Store')
                                    <span class="badge badge-success">{{ ucfirst($hour->service_type) }}</span>
                                    @elseif($hour->service_type == 'website')
                                    <span class="badge badge-primary">{{ ucfirst($hour->service_type) }}</span>
                                    @else
                                    <span class="badge badge-secondary">{{ ucfirst($hour->service_type) }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($hour->open_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($hour->close_time)->format('H:i') }}</td>
                                <td>
                                    @if($hour->status == 'open')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Open</span>
                                    @else
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hour->isCurrentlyOpen())
                                    <span class="badge badge-success"><i class="fas fa-circle"></i> Active Now</span>
                                    @else
                                    <span class="badge badge-secondary"><i class="far fa-circle"></i> Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.operational-hours.edit', $hour->id) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Status Card -->
    <div class="row">
        <!-- Left: Current Time -->
        <div class="col-md-6">
            <div class="card card-widget widget-user-2">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Current Status</h3>
                    <h5 class="widget-user-desc">{{ now()->translatedFormat('l, d M Y H:i') }}</h5>
                </div>
            </div>
        </div>

        <!-- Right: Service Status -->
        <div class="col-md-6">
            <div class="card card-widget" style="background-color: rgba(23, 162, 184, 0.1);">
                <div class="card-header">
                    <h3 class="card-title">Service Status</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="nav flex-column">

                        <!-- Website Status -->
                        <li class="nav-item border-bottom">
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Website</strong>
                                    @php
                                    $webOpen = \App\Models\OperationalHour::isOperational('Website');
                                    @endphp
                                    <span class="badge {{ $webOpen ? 'badge-success' : 'badge-danger' }}">
                                        {{ $webOpen ? 'OPEN' : 'CLOSED' }}
                                    </span>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    {{ \App\Models\OperationalHour::getOperationalMessage('Website') }}
                                </small>
                            </div>
                        </li>

                        <!-- Store Status -->
                        <li class="nav-item">
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Store</strong>
                                    @php
                                    $storeOpen = \App\Models\OperationalHour::isOperational('Store');
                                    @endphp
                                    <span class="badge {{ $storeOpen ? 'badge-success' : 'badge-danger' }}">
                                        {{ $storeOpen ? 'OPEN' : 'CLOSED' }}
                                    </span>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    {{ \App\Models\OperationalHour::getOperationalMessage('Store') }}
                                </small>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Update Modal (tidak berubah) -->
    <div class="modal fade" id="bulkUpdateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.operational-hours.bulk-update') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-edit"></i> Bulk Update Operational Hours
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Service Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="service_type" required>
                                <option value="">Select Service Type</option>
                                <option value="Store">Store</option>
                                <option value="website">Website</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select Days <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="day-{{ $day }}" name="days[]" value="{{ $day }}">
                                        <label class="custom-control-label" for="day-{{ $day }}">{{ $day }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Open Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="open_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Close Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="close_time" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" required>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Selected Days
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            if ($.fn.DataTable.isDataTable('#operationalHoursTable')) {
                $('#operationalHoursTable').DataTable().destroy();
            }

            const table = $('#operationalHoursTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 7]
                    }, // No & Action tidak bisa di-sort
                    {
                        "searchable": false,
                        "targets": [0, 3, 4, 5, 6, 7]
                    } // Kolom tertentu tidak ikut global search
                ]
            });

            // Filter berdasarkan Service Type (kolom ke-2, index 2)
            $('#serviceTypeFilter').on('change', function() {
                const serviceType = $(this).val();
                // Gunakan column().search() untuk filter spesifik kolom
                table.column(2).search(serviceType).draw();
            });
        });
    </script>
    @endpush
    @endsection