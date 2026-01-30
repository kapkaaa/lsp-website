<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('refund_request_status')->nullable()->default(null)->after('payment_method'); // requested, approved, rejected
            $table->text('refund_reason')->nullable()->after('refund_request_status');
            $table->text('refund_rejection_reason')->nullable()->after('refund_reason');
            $table->timestamp('refund_request_date')->nullable()->after('refund_rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'refund_request_status',
                'refund_reason',
                'refund_rejection_reason',
                'refund_request_date'
            ]);
        });
    }
};
