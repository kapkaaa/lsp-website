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
        if (!Schema::hasTable('product_photos')) {
            Schema::create('product_photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_detail_id');
                $table->string('photo_url');
                $table->timestamps();

                // Foreign key constraint
                $table->foreign('product_detail_id')->references('id')->on('product_details')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_photos')) {
            Schema::dropIfExists('product_photos');
        }
    }
};
