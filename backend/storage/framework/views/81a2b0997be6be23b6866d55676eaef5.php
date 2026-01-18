<?php $__env->startSection('page_title', 'Order Detail'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.orders.index')); ?>">Orders</a></li>
<li class="breadcrumb-item active"><?php echo e($order->order_code); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
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
                            <strong>Order Code:</strong> <?php echo e($order->order_code); ?><br>
                            <strong>Order Date:</strong> <?php echo e($order->created_at->format('d M Y H:i')); ?><br>
                            <strong>Payment Method:</strong> <?php echo e(ucfirst($order->payment_method)); ?>

                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            <?php if($order->payment_status == 'pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                            <?php elseif($order->payment_status == 'paid'): ?>
                            <span class="badge badge-success">Paid</span>
                            <?php else: ?>
                            <span class="badge badge-danger">Rejected</span>
                            <?php endif; ?>
                            <br>
                            <strong>Order Status:</strong>
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
                            <br>
                            <?php if($order->order_status != 'cancelled' && $order->approver): ?>
                            <strong>Verified By:</strong> <?php echo e($order->approver->name); ?>

                            <?php endif; ?>
                            <?php if($order->order_status == 'cancelled'): ?>
                            <strong>Rejected By:</strong> <?php echo e($order->approver->name); ?>

                            <?php endif; ?>
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
                    <strong>Name:</strong> <?php echo e($order->buyer->name); ?><br>
                    <strong>Phone:</strong> <?php echo e($order->buyer->phone); ?><br>
                    <strong>Address:</strong> <?php echo e($order->buyer->address ?? '-'); ?><br>
                    <strong>City:</strong> <?php echo e($order->buyer->city); ?><br>
                    <strong>Destination City:</strong> <?php echo e($order->destination_city); ?>

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
                                <th class="text-right">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php echo e($detail->product->brand->name ?? '-'); ?>

                                    |
                                    <?php echo e($detail->product->type->name ?? '-'); ?>

                                    <?php echo e($detail->product->name ?? ''); ?>

                                    <?php echo e($detail->product_detail->color->name ?? ''); ?>

                                    |
                                    <?php echo e($detail->product_detail->size->name ?? '-'); ?>

                                </td>
                                <td class="text-right">Rp <?php echo e(number_format($detail->unit_price, 0, ',', '.')); ?></td>
                                <td class="text-center"><?php echo e($detail->quantity); ?></td>
                                <td class="text-right">Rp <?php echo e(number_format($detail->total, 0, ',', '.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                <td class="text-right"><strong>Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <strong>Shipping Cost (<?php echo e($order->weight); ?> kg):</strong><br>
                                    <small class="text-muted"><?php echo e($order->shippingRate->region); ?></small>
                                </td>
                                <td class="text-right"><strong>Rp <?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></strong></td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="3" class="text-right">
                                    <h5><strong>Total Payment:</strong></h5>
                                </td>
                                <td class="text-right">
                                    <h5><strong>Rp <?php echo e(number_format($order->total_payment, 0, ',', '.')); ?></strong></h5>
                                </td>
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
                    <?php if($order->canBeVerified()): ?>
                    <form action="<?php echo e(route('admin.orders.verify-payment', $order->id)); ?>" method="POST" class="mb-2">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Verify this payment?')">
                            <i class="fas fa-check"></i> Verify Payment
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php if($order->canBeRejected()): ?>
                    <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#rejectModal">
                        <i class="fas fa-times"></i> Reject Payment
                    </button>
                    <?php endif; ?>

                    <?php if($order->canBeShipped()): ?>
                    <form action="<?php echo e(route('admin.orders.update-status', $order->id)); ?>" method="POST" class="mb-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="order_status" value="shipped">
                        <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Mark as shipped?')">
                            <i class="fas fa-shipping-fast"></i> Mark as Shipped
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if($order->isShipped()): ?>
                    <form action="<?php echo e(route('admin.orders.update-status', $order->id)); ?>" method="POST" class="mb-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="order_status" value="completed">
                        <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Mark as completed?')">
                            <i class="fas fa-check-double"></i> Mark as Completed
                        </button>
                    </form>
                    <?php endif; ?>

                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>

            <!-- Payment Proof -->
            <?php if($order->payment_proof): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Proof</h3>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo e(asset('storage/' . $order->payment_proof)); ?>"
                        alt="Payment Proof"
                        class="img-fluid"
                        style="max-height: 400px; cursor: pointer;"
                        onclick="window.open(this.src, '_blank')">
                    <p class="text-sm text-muted mt-2">Click image to view full size</p>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-image fa-3x mb-3"></i><br>
                    No payment proof uploaded yet
                </div>
            </div>
            <?php endif; ?>

            <!-- Payment Information -->
            <?php if($order->payment): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Information</h3>
                </div>
                <div class="card-body">
                    <strong>Payment Method:</strong> <?php echo e(ucfirst($order->payment->payment_method)); ?><br>
                    <strong>Gross Amount:</strong> Rp <?php echo e(number_format($order->payment->gross_amount, 0, ',', '.')); ?><br>
                    <?php if($order->payment->midtrans_transaction_id): ?>
                    <strong>Transaction ID:</strong> <?php echo e($order->payment->midtrans_transaction_id); ?><br>
                    <?php endif; ?>
                    <strong>Income:</strong> Rp <?php echo e(number_format($order->payment->income, 0, ',', '.')); ?><br>
                    <strong>Profit:</strong> Rp <?php echo e(number_format($order->payment->profit, 0, ',', '.')); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.orders.reject-payment', $order->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DREAM\LSP\DistroZone-Web\backend\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>