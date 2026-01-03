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
        Schema::table('products', function (Blueprint $table) {
            // Add cost_price and selling_price columns if they don't exist
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('type_id');
            }
            if (!Schema::hasColumn('products', 'selling_price')) {
                $table->decimal('selling_price', 10, 2)->nullable()->after('cost_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove the new columns if they exist
            if (Schema::hasColumn('products', 'selling_price')) {
                $table->dropColumn('selling_price');
            }
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
        });
    }
};
