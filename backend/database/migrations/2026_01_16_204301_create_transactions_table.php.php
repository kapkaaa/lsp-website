<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('transaction_code')->nullable();
            $table->bigInteger('total')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_status')->nullable();
            $table->bigInteger('cash_received')->nullable();
            $table->bigInteger('change_given')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};