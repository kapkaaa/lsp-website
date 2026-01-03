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
        if (!Schema::hasTable('product_details')) {
            Schema::create('product_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('size_id');
                $table->unsignedBigInteger('color_id');
                $table->integer('stock')->default(0);
                $table->unsignedBigInteger('product_id');
                $table->boolean('status')->default(true);
                $table->timestamps();

                // Foreign key constraint
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_details')) {
            Schema::dropIfExists('product_details');
        }
    }
};
