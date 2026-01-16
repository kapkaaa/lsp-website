<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('shipping_rate_id')->nullable()->constrained('shipping_rates')->onDelete('restrict');
            $table->string('order_code')->nullable();
            $table->bigInteger('subtotal')->nullable();
            $table->bigInteger('weight')->nullable();
            $table->bigInteger('shipping_cost')->nullable();
            $table->bigInteger('total_payment')->nullable();
            $table->string('destination_city')->nullable();
            $table->text('payment_proof')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('order_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
