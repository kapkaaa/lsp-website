<?php $__env->startSection('title', 'Orders Management'); ?>  

<?php $__env->startSection('content_header'); ?>
    <h1>Online Orders</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                    <form action="<?php echo e(route('admin.orders.index')); ?>" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Order code or customer name"
                                           value="<?php echo e(request('search')); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="">All</option>
                                        <option value="pending" <?php echo e(request('payment_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="paid" <?php echo e(request('payment_status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                        <option value="rejected" <?php echo e(request('payment_status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Order Status</label>
                                    <select name="order_status" class="form-control">
                                        <option value="">All</option>
                                        <option value="pending" <?php echo e(request('order_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="verified" <?php echo e(request('order_status') == 'verified' ? 'selected' : ''); ?>>Verified</option>
                                        <option value="shipped" <?php echo e(request('order_status') == 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                                        <option value="completed" <?php echo e(request('order_status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                        <option value="cancelled" <?php echo e(request('order_status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date"
                                           name="start_date"
                                           class="form-control"
                                           value="<?php echo e(request('start_date')); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date"
                                           name="end_date"
                                           class="form-control"
                                           value="<?php echo e(request('end_date')); ?>">
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
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('cashier.orders.show', $order->id)); ?>">
                                            <strong><?php echo e($order->order_code); ?></strong>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo e($order->buyer->name); ?><br>
                                        <small class="text-muted"><?php echo e($order->buyer->phone); ?></small>
                                    </td>
                                    <td><?php echo e($order->destination_city); ?></td>
                                    <td>
                                        <strong>Rp <?php echo e(number_format($order->total_payment, 0, ',', '.')); ?></strong><br>
                                        <small class="text-muted">
                                            Subtotal: Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?><br>
                                            Shipping: Rp <?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?>

                                        </small>
                                    </td>
                                    <td>
                                        <?php if($order->payment_status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif($order->payment_status == 'paid'): ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($order->order_status == 'pending'): ?>
                                            <span class="badge badge-secondary">Pending</span>
                                        <?php elseif($order->order_status == 'verified'): ?>
                                            <span class="badge badge-info">Verified</span>
                                        <?php elseif($order->order_status == 'shipped'): ?>
                                            <span class="badge badge-primary">Shipped</span>
                                        <?php elseif($order->order_status == 'completed'): ?>
                                            <span class="badge badge-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('cashier.orders.show', $order->id)); ?>"
                                           class="btn btn-sm btn-info"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                        No orders found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php echo e($orders->appends(request()->query())->links('pagination::bootstrap-4')); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cashier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DREAM\LSP\DistroZone-Web\backend\resources\views/cashier/orders/index.blade.php ENDPATH**/ ?>