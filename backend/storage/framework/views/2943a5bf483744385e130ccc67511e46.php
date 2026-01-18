<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
<div class="container-fluid">
    
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo e($totalProducts); ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo e($totalStock); ?></h3>
                    <p>Total Stock</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 class="text-white"><?php echo e($pendingOrders); ?></h3>
                    <p class="text-white">Pending Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo e($totalCustomers); ?></h3>
                    <p>Total Customers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today Online Sales</span>
                    <span class="info-box-number">Rp <?php echo e(number_format($todayOnlineSales, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-cash-register"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today Offline Sales</span>
                    <span class="info-box-number">Rp <?php echo e(number_format($todayOfflineSales, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">This Month Total</span>
                    <span class="info-box-number">Rp <?php echo e(number_format($thisMonthTotal, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Sales Last 7 Days
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Low Stock Products
                    </h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="item">
                                <div class="product-info">
                                    <a href="<?php echo e(route('admin.products.show', $product->id)); ?>" class="product-title">
                                        <?php echo e($product->name); ?>

                                        <span class="badge badge-warning float-right"><?php echo e($product->getAvailableStock()); ?> pcs</span>
                                    </a>
                                    <span class="product-description">
                                        <?php echo e($product->brand->name); ?> - <?php echo e($product->type->name); ?>

                                    </span>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-description text-muted">No low stock products</span>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="uppercase">View All Products</a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Recent Orders
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>">
                                            <?php echo e($order->order_code); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($order->buyer->name); ?></td>
                                    <td>Rp <?php echo e(number_format($order->total_payment, 0, ',', '.')); ?></td>
                                    <td>
                                        <span class="badge badge-payment-<?php echo e($order->payment_status); ?>">
                                            <?php echo e(ucfirst($order->payment_status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-order-<?php echo e($order->order_status); ?>">
                                            <?php echo e(ucfirst($order->order_status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No recent orders</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
$(document).ready(function() {
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesData = <?php echo json_encode($last7Days, 15, 512) ?>;
    
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: salesData.map(item => item.date),
            datasets: [
                {
                    label: 'Online Sales',
                    data: salesData.map(item => item.online),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Offline Sales',
                    data: salesData.map(item => item.offline),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DREAM\LSP\DistroZone-Web\backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>