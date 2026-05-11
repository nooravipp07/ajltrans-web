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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('tier');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('customer_nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['tier']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['customer_nik']);
        });
    }
};
