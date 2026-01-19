<?php $__env->startSection('favicons'); ?>
    <link rel="icon" type="image/png" href="<?php echo e(asset('logo.png')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title', config('app.name')); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $__env->yieldContent('page_title'); ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                </ol>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('warning')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('main_content'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .content-wrapper {
            background-color: #f4f6f9;
        }
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
        }
        .badge-status-active {
            background-color: #28a745;
        }
        .badge-status-inactive {
            background-color: #dc3545;
        }
        .badge-payment-pending {
            background-color: #ffc107;
        }
        .badge-payment-paid {
            background-color: #28a745;
        }
        .badge-order-pending {
            background-color: #6c757d;
        }
        .badge-order-verified {
            background-color: #17a2b8;
        }
        .badge-order-shipped {
            background-color: #007bff;
        }
        .badge-order-completed {
            background-color: #28a745;
        }
        .badge-order-cancelled {
            background-color: #dc3545;
        }
        .img-thumbnail-list {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .product-image-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            margin: 5px;
        }
    </style>
    <?php echo $__env->yieldPushContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        // Auto hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Format rupiah
        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        // Input rupiah format
        $(document).on('keyup', '.rupiah-input', function() {
            var value = $(this).val().replace(/\D/g, '');
            $(this).val(formatRupiah(value));
        });
    </script>
    <?php echo $__env->yieldPushContent('js'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DREAM\LSP\DistroZone-Web\backend\resources\views/layouts/admin.blade.php ENDPATH**/ ?>