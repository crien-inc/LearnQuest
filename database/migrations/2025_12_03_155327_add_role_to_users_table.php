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
            $table->enum('role', ['user', 'admin'])->default('user')->after('email');
            $table->integer('total_points')->default(0)->after('role');
            $table->integer('level')->default(1)->after('total_points');
            $table->integer('streak_days')->default(0)->after('level');
            $table->timestamp('last_activity_at')->nullable()->after('streak_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'total_points', 'level', 'streak_days', 'last_activity_at']);
        });
    }
};
