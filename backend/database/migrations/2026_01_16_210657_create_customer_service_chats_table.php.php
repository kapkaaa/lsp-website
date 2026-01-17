<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_service_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->text('message')->nullable();
            $table->string('message_type', 50)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_read')->nullable();
            $table->timestamps();
            
            $table->index('sender_id');
            $table->index('receiver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_service_chats');
    }
};