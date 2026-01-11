@extends('layouts.admin')

@section('page_title', 'Stock Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Stock</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <!-- Summary -->
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-tshirt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Products</span>
                    <span class="info-box-number">{{ $totalProducts }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Stock</span>
                    <span class="info-box-number">{{ $totalStock }} pcs</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Low Stock</span>
                    <span class="info-box-number">{{ $lowStockProducts }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Out of Stock</span>
                    <span class="info-box-number">{{ $outOfStockProducts }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Stock List</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reports.export-pdf', ['type' => 'stock']) }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover" id="stockTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th class="text-center">Total Stock</th>
                                <th class="text-center">Available</th>
                                <th class="text-right">Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['brand'] }}</td>
                                    <td>{{ $product['type'] }}</td>
                                    <td class="text-center">{{ $product['total_stock'] }}</td>
                                    <td class="text-center">{{ $product['available_stock'] }}</td>
                                    <td class="text-right">Rp {{ number_format($product['stock_value'], 0, ',', '.') }}</td>
                                    <td>
                                        @if($product['status'] == 'Out of Stock')
                                            <span class="badge badge-danger">{{ $product['status'] }}</span>
                                        @elseif($product['status'] == 'Low Stock')
                                            <span class="badge badge-warning">{{ $product['status'] }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $product['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="6" class="text-right">Total Value:</th>
                                <th class="text-right">Rp {{ number_format($totalValue, 0, ',', '.') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#stockTable').DataTable().destroy();
    $('#stockTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});
</script>
@endpush