<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('restrict');
            $table->bigInteger('product_detail_id');
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('unit_price')->nullable();
            $table->bigInteger('total')->nullable();
            $table->integer('product_id')->nullable();
            $table->timestamps();
            
            $table->index('product_detail_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};