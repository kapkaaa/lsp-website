{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')

@section('page_title', 'Order Detail')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_code }}</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Details Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Order Code:</strong> {{ $order->order_code }}<br>
                            <strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}<br>
                            <strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            @if($order->payment_status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($order->payment_status == 'paid')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                            <br>
                            <strong>Order Status:</strong>
                            @if($order->order_status == 'pending')
                                <span class="badge badge-secondary">Pending</span>
                            @elseif($order->order_status == 'verified')
                                <span class="badge badge-info">Verified</span>
                            @elseif($order->order_status == 'shipped')
                                <span class="badge badge-primary">Shipped</span>
                            @elseif($order->order_status == 'completed')
                                <span class="badge badge-success">Completed</span>
                            @else
                                <span class="badge badge-danger">Cancelled</span>
                            @endif
                            <br>
                            @if($order->order_status != 'cancelled' && $order->approver)
                                <strong>Verified By:</strong> {{ $order->approver->name }}
                                @endif
                            @if ($order->order_status == 'cancelled')
                                <strong>Rejected By:</strong> {{ $order->approver->name }} 
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <div class="card-body">
                    <strong>Name:</strong> {{ $order->buyer->name }}<br>
                    <strong>Phone:</strong> {{ $order->buyer->phone }}<br>
                    <strong>Address:</strong> {{ $order->buyer->address ?? '-' }}<br>
                    <strong>City:</strong> {{ $order->buyer->city }}<br>
                    <strong>Destination City:</strong> {{ $order->destination_city }}
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Items</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th class="text-right">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->product->brand->name }}</td>
                                    <td>{{ $detail->product->type->name }}</td>
                                    <td class="text-right">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                                <td class="text-right"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">
                                    <strong>Shipping Cost ({{ $order->weight }} kg):</strong><br>
                                    <small class="text-muted">{{ $order->shippingRate->region }}</small>
                                </td>
                                <td class="text-right"><strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right"><h5><strong>Total Payment:</strong></h5></td>
                                <td class="text-right"><h5><strong>Rp {{ number_format($order->total_payment, 0, ',', '.') }}</strong></h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions & Payment Proof -->
        <div class="col-md-4">
            <!-- Actions Card -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    @if($order->canBeVerified())
                        <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Verify this payment?')">
                                <i class="fas fa-check"></i> Verify Payment
                            </button>
                        </form>
                    @endif
                    @if ($order->canBeRejected())
                    <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times"></i> Reject Payment
                    </button>
                    @endif

                    @if($order->canBeShipped())
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_status" value="shipped">
                            <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Mark as shipped?')">
                                <i class="fas fa-shipping-fast"></i> Mark as Shipped
                            </button>
                        </form>
                    @endif

                    @if($order->isShipped())
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_status" value="completed">
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Mark as completed?')">
                                <i class="fas fa-check-double"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>

            <!-- Payment Proof -->
            @if($order->payment_proof)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Proof</h3>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                             alt="Payment Proof" 
                             class="img-fluid"
                             style="max-height: 400px; cursor: pointer;"
                             onclick="window.open(this.src, '_blank')">
                        <p class="text-sm text-muted mt-2">Click image to view full size</p>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center text-muted">
                        <i class="fas fa-image fa-3x mb-3"></i><br>
                        No payment proof uploaded yet
                    </div>
                </div>
            @endif

            <!-- Payment Information -->
            @if($order->payment)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Information</h3>
                    </div>
                    <div class="card-body">
                        <strong>Payment Method:</strong> {{ ucfirst($order->payment->payment_method) }}<br>
                        <strong>Gross Amount:</strong> Rp {{ number_format($order->payment->gross_amount, 0, ',', '.') }}<br>
                        @if($order->payment->midtrans_transaction_id)
                            <strong>Transaction ID:</strong> {{ $order->payment->midtrans_transaction_id }}<br>
                        @endif
                        <strong>Income:</strong> Rp {{ number_format($order->payment->income, 0, ',', '.') }}<br>
                        <strong>Profit:</strong> Rp {{ number_format($order->payment->profit, 0, ',', '.') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.reject-payment', $order->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Reject Payment</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  name="rejection_reason" 
                                  rows="3" 
                                  placeholder="Enter reason for rejection" 
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection