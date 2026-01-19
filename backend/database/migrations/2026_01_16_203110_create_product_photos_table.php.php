<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_detail_id')->nullable();
            $table->text('photo_url')->nullable();
            $table->timestamps();
            
            $table->foreign('product_detail_id')
                  ->references('id')
                  ->on('product_details')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_photos');
    }
};