<?php $__env->startSection('title', 'Customer Service'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Customer Service</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Customer List -->
        <div class="col-md-4">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Customers
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary" id="totalCustomers"><?php echo e($customers->count()); ?></span>
                    </div>
                </div>
                <div class="card-body" style="height: 600px; overflow-y: auto;">
                    <div class="list-group" id="customerList">
                        <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="#"
                               class="list-group-item list-group-item-action customer-item <?php echo e($loop->first ? 'active' : ''); ?>"
                               data-customer-id="<?php echo e($customer->id); ?>"
                               data-customer-name="<?php echo e($customer->name); ?>">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="fas fa-user"></i> <?php echo e($customer->name); ?>

                                    </h6>
                                    <small class="unread-badge-<?php echo e($customer->id); ?>" style="display: none;">
                                        <span class="badge badge-danger">New</span>
                                    </small>
                                </div>
                                <p class="mb-1 text-sm">
                                    <i class="fas fa-phone"></i> <?php echo e($customer->phone); ?>

                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo e($customer->city); ?>

                                </small>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                No customers have contacted yet
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header">
                    <h3 class="card-title" id="chatTitle">
                        <i class="fas fa-comments"></i> Chat Messages
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" id="refreshChat">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="direct-chat-messages" id="chatMessages" style="height: 450px;">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-comments fa-3x mb-3"></i><br>
                            Select a customer to view messages
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form id="chatForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="receiverId">
                        <div class="input-group">
                            <input type="text"
                                   id="messageInput"
                                   placeholder="Type message..."
                                   class="form-control"
                                   disabled>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="sendBtn" disabled>
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Replies -->
            <div class="card collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i> Quick Replies
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="btn-group-vertical btn-block">
                        <button type="button" class="btn btn-outline-primary btn-sm quick-reply" data-message="Halo! Ada yang bisa kami bantu?">
                            Greeting
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-reply" data-message="Terima kasih telah menghubungi DistroZone">
                            Thank You
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-reply" data-message="Jam operasional kami: 10:00 - 17:00">
                            Operational Hours
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-reply" data-message="Untuk informasi lebih lanjut, silakan hubungi: 081234567890">
                            Contact Info
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
$(document).ready(function() {
    let selectedCustomerId = null;
    let selectedCustomerName = null;
    let autoRefreshInterval = null;

    // Customer item click
    $(document).on('click', '.customer-item', function(e) {
        e.preventDefault();

        // Remove active from all
        $('.customer-item').removeClass('active');
        $(this).addClass('active');

        selectedCustomerId = $(this).data('customer-id');
        selectedCustomerName = $(this).data('customer-name');

        $('#receiverId').val(selectedCustomerId);
        $('#chatTitle').html('<i class="fas fa-comments"></i> Chat with ' + selectedCustomerName);
        $('#messageInput').prop('disabled', false);
        $('#sendBtn').prop('disabled', false);

        loadMessages(selectedCustomerId);

        // Start auto refresh
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        autoRefreshInterval = setInterval(function() {
            loadMessages(selectedCustomerId, true);
        }, 5000); // Refresh every 5 seconds
    });

    // Load messages
    function loadMessages(userId, silent = false) {
        $.ajax({
            url: '<?php echo e(route("cashier.customer-service.messages", ":userId")); ?>'.replace(':userId', userId),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayMessages(response.data);

                    // Hide unread badge for this customer
                    $('.unread-badge-' + userId).hide();

                    if (!silent) {
                        scrollToBottom();
                    }
                }
            },
            error: function(xhr) {
                console.error('Error loading messages:', xhr);
            }
        });
    }

    // Display messages
    function displayMessages(messages) {
        let html = '';

        if (messages.length === 0) {
            html = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                    No messages yet
                </div>
            `;
        } else {
            messages.forEach(function(msg) {
                let messageClass = msg.sender_id == selectedCustomerId ? '' : 'right';
                let senderName = msg.sender_id == selectedCustomerId ? selectedCustomerName : 'You';
                let time = new Date(msg.sent_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});

                html += `
                    <div class="direct-chat-msg ${messageClass}">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name ${messageClass ? 'float-right' : 'float-left'}">${senderName}</span>
                            <span class="direct-chat-timestamp ${messageClass ? 'float-left' : 'float-right'}">${time}</span>
                        </div>
                        <img class="direct-chat-img" src="https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=random" alt="${senderName}">
                        <div class="direct-chat-text">
                            ${msg.message}
                        </div>
                    </div>
                `;
            });
        }

        $('#chatMessages').html(html);
    }

    // Send message
    $('#chatForm').off('submit').on('submit', (function(e) {
        e.preventDefault();

        let message = $('#messageInput').val().trim();
        let receiverId = $('#receiverId').val();

        if (!message || !receiverId) {
            return;
        }

        $.ajax({
            url: '<?php echo e(route("cashier.customer-service.send")); ?>',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                receiver_id: receiverId,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    $('#messageInput').val('');
                    loadMessages(receiverId);
                    scrollToBottom();
                }
            },
            error: function(xhr) {
                console.error('Error sending message:', xhr);
                alert('Failed to send message');
            }
        });
    }));

    // Quick reply
    $('.quick-reply').click(function() {
        let message = $(this).data('message');
        $('#messageInput').val(message);
        $('#messageInput').focus();
    });

    // Refresh chat
    $('#refreshChat').click(function() {
        if (selectedCustomerId) {
            loadMessages(selectedCustomerId);
        }
    });

    // Scroll to bottom
    function scrollToBottom() {
        let chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto load first customer
    <?php if($customers->isNotEmpty()): ?>
        $('.customer-item:first').click();
    <?php endif; ?>

    // Check unread messages periodically
    setInterval(function() {
        $.ajax({
            url: '<?php echo e(route("cashier.customer-service.unread-count")); ?>',
            type: 'GET',
            success: function(response) {
                if (response.count > 0) {
                    // You can update UI to show unread count
                }
            }
        });
    }, 10000); // Check every 10 seconds

    // Clear interval on page leave
    $(window).on('beforeunload', function() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('css'); ?>
<style>
.customer-item {
    cursor: pointer;
    transition: all 0.3s;
}
.customer-item:hover {
    background-color: #f4f6f9;
}
.customer-item.active {
    background-color: #007bff;
    color: white !important;
}
.customer-item.active h6,
.customer-item.active p,
.customer-item.active small {
    color: white !important;
}
.direct-chat-messages {
    overflow-y: auto;
}
.direct-chat-msg {
    margin-bottom: 20px;
}
.quick-reply {
    margin-bottom: 5px;
    text-align: left;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.cashier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DREAM\LSP\DistroZone-Web\backend\resources\views/cashier/customer_service/index.blade.php ENDPATH**/ ?>