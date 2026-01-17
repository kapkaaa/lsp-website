@extends('layouts.admin')

@section('page_title', 'Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-chart-line fa-4x text-primary mb-3"></i>
                    </div>
                    <h3 class="profile-username text-center">Sales Report</h3>
                    <p class="text-muted text-center">View online and offline sales data</p>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-eye"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-success card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-boxes fa-4x text-success mb-3"></i>
                    </div>
                    <h3 class="profile-username text-center">Stock Report</h3>
                    <p class="text-muted text-center">View product stock and inventory</p>
                    <a href="{{ route('admin.reports.stock') }}" class="btn btn-success btn-block">
                        <i class="fas fa-eye"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-warning card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-money-bill-wave fa-4x text-warning mb-3"></i>
                    </div>
                    <h3 class="profile-username text-center">Profit Report</h3>
                    <p class="text-muted text-center">View profit and revenue analysis</p>
                    <a href="{{ route('admin.reports.profit') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-eye"></i> View Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection