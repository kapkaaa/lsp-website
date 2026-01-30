@extends('layouts.admin')

@section('page_title', 'Orders Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Filter Orders</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Order code or customer name" 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="">All</option>
                                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="rejected" {{ request('payment_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Order Status</label>
                                    <select name="order_status" class="form-control">
                                        <option value="">All</option>
                                        <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ request('order_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="completed" {{ request('order_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" 
                                           name="start_date" 
                                           class="form-control" 
                                           value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" 
                                           name="end_date" 
                                           class="form-control" 
                                           value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order List</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th>Destination</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}">
                                            <strong>{{ $order->order_code }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{ $order->buyer->name }}<br>
                                        <small class="text-muted">{{ $order->buyer->phone }}</small>
                                    </td>
                                    <td>{{ $order->destination_city }}</td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total_payment, 0, ',', '.') }}</strong><br>
                                        <small class="text-muted">
                                            Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}<br>
                                            Shipping: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($order->payment_status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($order->payment_status == 'refunded')
                                            <span class="badge badge-info">Refunded</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->order_status == 'pending')
                                            <span class="badge badge-secondary">Pending</span>
                                        @elseif($order->order_status == 'verified')
                                            <span class="badge badge-info">Verified</span>
                                        @elseif($order->order_status == 'shipped')
                                            <span class="badge badge-primary">Shipped</span>
                                        @elseif($order->order_status == 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($order->order_status == 'refunded')
                                            <span class="badge badge-info">Refunded</span>
                                        @else
                                            <span class="badge badge-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection