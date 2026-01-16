<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('size_id')->nullable();
            $table->integer('color_id')->nullable();
            $table->integer('stock')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->string('barcode', 13);
            $table->timestamps();
            
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
