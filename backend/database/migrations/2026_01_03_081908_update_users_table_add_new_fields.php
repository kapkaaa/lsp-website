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
        Schema::table('users', function (Blueprint $table) {
            // Add the new fields if they don't exist
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->after('id')->default(1);
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik')->after('password')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->after('nik')->nullable();
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->after('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->after('city')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->after('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true)->after('profile_photo');
            }

            // Drop email field if it exists
            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }

            // Drop email_verified_at field if it exists
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the new fields
            $table->dropColumn([
                'role_id',
                'username',
                'nik',
                'address',
                'city',
                'phone',
                'profile_photo',
                'status'
            ]);

            // Add back the original fields if they were removed
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique();
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });
    }
};
