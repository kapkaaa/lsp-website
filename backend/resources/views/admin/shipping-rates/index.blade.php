@extends('layouts.admin')

@section('page_title', 'Shipping Rates')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Shipping Rates</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-truck"></i> Shipping Rates Management
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="shippingRatesTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Region</th>
                                    <th>Price per Kg</th>
                                    <th>Example (3 kaos)</th>
                                    <th>Total Orders</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shippingRates as $index => $rate)
                                    <tr>
                                        <td>{{ $shippingRates->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $rate->region }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-success badge-lg">
                                                Rp {{ number_format($rate->price_per_kg, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calculator"></i> 
                                                1 kg × Rp {{ number_format($rate->price_per_kg, 0, ',', '.') }} = 
                                                <strong>Rp {{ number_format($rate->price_per_kg, 0, ',', '.') }}</strong>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $rate->orders->count() }} orders
                                            </span>
                                        </td>
                                        <td>{{ $rate->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $shippingRates->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Shipping Rules
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>1 kg = 3 kaos</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Kurang dari 3 kaos tetap dihitung 1 kg
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Hanya melayani pengiriman ke Pulau Jawa
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Harga per kg sesuai wilayah tujuan
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator"></i> Shipping Calculator Example
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Items</th>
                                <th>Weight</th>
                                <th>Example Price (Jakarta)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1-3 kaos</td>
                                <td>1 kg</td>
                                <td>Rp 24.000</td>
                            </tr>
                            <tr>
                                <td>4-6 kaos</td>
                                <td>2 kg</td>
                                <td>Rp 48.000</td>
                            </tr>
                            <tr>
                                <td>7-9 kaos</td>
                                <td>3 kg</td>
                                <td>Rp 72.000</td>
                            </tr>
                            <tr>
                                <td>10-12 kaos</td>
                                <td>4 kg</td>
                                <td>Rp 96.000</td>
                            </tr>
                        </tbody>
                    </table>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Formula: ceil(total_items / 3) × price_per_kg
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#shippingRatesTable').DataTable().destroy();
    $('#shippingRatesTable').DataTable({
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