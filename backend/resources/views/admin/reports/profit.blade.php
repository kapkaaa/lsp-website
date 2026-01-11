@extends('layouts.admin')

@section('page_title', 'Profit Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Profit</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.profit') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label class="mr-2">Period:</label>
                            <input type="date" 
                                   name="start_date" 
                                   class="form-control" 
                                   value="{{ $startDate }}"
                                   required>
                        </div>
                        <div class="form-group mr-2">
                            <label class="mr-2">to</label>
                            <input type="date" 
                                   name="end_date" 
                                   class="form-control" 
                                   value="{{ $endDate }}"
                                   required>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.reports.profit') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($totalCost, 0, ',', '.') }}</h3>
                    <p>Total Cost</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
                    <p>Net Profit</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($profitMargin, 2) }}%</h3>
                    <p>Profit Margin</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown by Channel -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart"></i> Online Sales Profit
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th width="40%">Revenue:</th>
                            <td class="text-right">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Cost of Goods:</th>
                            <td class="text-right text-danger">Rp {{ number_format($onlineCost, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-light">
                            <th>Gross Profit:</th>
                            <td class="text-right">
                                <strong>Rp {{ number_format($onlineProfit, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Profit Margin:</th>
                            <td class="text-right">
                                <span class="badge badge-success">
                                    {{ $onlineRevenue > 0 ? number_format(($onlineProfit / $onlineRevenue) * 100, 2) : 0 }}%
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $onlineRevenue > 0 ? ($onlineProfit / $onlineRevenue) * 100 : 0 }}%">
                        </div>
                    </div>

                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Based on {{ $onlineRevenue > 0 ? 'completed' : 'no' }} online orders
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">
                        <i class="fas fa-cash-register"></i> Offline Sales Profit
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th width="40%">Revenue:</th>
                            <td class="text-right">Rp {{ number_format($offlineRevenue, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Cost of Goods:</th>
                            <td class="text-right text-danger">Rp {{ number_format($offlineCost, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-light">
                            <th>Gross Profit:</th>
                            <td class="text-right">
                                <strong>Rp {{ number_format($offlineProfit, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Profit Margin:</th>
                            <td class="text-right">
                                <span class="badge badge-success">
                                    {{ $offlineRevenue > 0 ? number_format(($offlineProfit / $offlineRevenue) * 100, 2) : 0 }}%
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $offlineRevenue > 0 ? ($offlineProfit / $offlineRevenue) * 100 : 0 }}%">
                        </div>
                    </div>

                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Based on {{ $offlineRevenue > 0 ? 'completed' : 'no' }} POS transactions
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i> Profit Distribution
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="profitChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-table"></i> Profit Summary
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Channel</th>
                                <th class="text-right">Revenue</th>
                                <th class="text-right">COGS</th>
                                <th class="text-right">Gross Profit</th>
                                <th class="text-right">Margin</th>
                                <th class="text-center">Contribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Online Sales</strong></td>
                                <td class="text-right">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</td>
                                <td class="text-right text-danger">Rp {{ number_format($onlineCost, 0, ',', '.') }}</td>
                                <td class="text-right text-success">
                                    <strong>Rp {{ number_format($onlineProfit, 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-right">
                                    {{ $onlineRevenue > 0 ? number_format(($onlineProfit / $onlineRevenue) * 100, 2) : 0 }}%
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">
                                        {{ $totalRevenue > 0 ? number_format(($onlineRevenue / $totalRevenue) * 100, 1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Offline Sales (POS)</strong></td>
                                <td class="text-right">Rp {{ number_format($offlineRevenue, 0, ',', '.') }}</td>
                                <td class="text-right text-danger">Rp {{ number_format($offlineCost, 0, ',', '.') }}</td>
                                <td class="text-right text-success">
                                    <strong>Rp {{ number_format($offlineProfit, 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-right">
                                    {{ $offlineRevenue > 0 ? number_format(($offlineProfit / $offlineRevenue) * 100, 2) : 0 }}%
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">
                                        {{ $totalRevenue > 0 ? number_format(($offlineRevenue / $totalRevenue) * 100, 1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th>TOTAL</th>
                                <th class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                                <th class="text-right text-danger">Rp {{ number_format($totalCost, 0, ',', '.') }}</th>
                                <th class="text-right text-success">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                </th>
                                <th class="text-right">
                                    <strong>{{ number_format($profitMargin, 2) }}%</strong>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success">100%</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info-circle"></i> Notes:</h5>
                <p>
                    <strong>Revenue:</strong> Total sales from all completed transactions<br>
                    <strong>COGS (Cost of Goods Sold):</strong> Total cost price of products sold<br>
                    <strong>Gross Profit:</strong> Revenue - COGS (excluding operational costs)<br>
                    <strong>Profit Margin:</strong> (Gross Profit / Revenue) × 100%
                </p>
                <p class="mb-0">
                    <small class="text-muted">
                        Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Profit Chart
    var ctx = document.getElementById('profitChart').getContext('2d');
    
    // Data untuk chart
    var chartData = {
        labels: ['Online Revenue', 'Offline Revenue', 'Total Cost'],
        datasets: [{
            data: @json([$onlineRevenue ?? 0, $offlineRevenue ?? 0, $totalCost ?? 0]),
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 2
        }]
    };

    // Opsi untuk chart
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                        return label;
                    }
                }
            }
        }
    };

    // Inisialisasi chart
    new Chart(ctx, {
        type: 'doughnut',
        data: chartData,
        options: chartOptions
    });
});
</script>
@endpush