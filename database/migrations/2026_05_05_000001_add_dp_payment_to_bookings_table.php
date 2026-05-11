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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('dp_amount')->default(0)->after('total_harga');
            $table->enum('dp_status', ['pending', 'paid', 'confirmed'])->default('pending')->after('dp_amount');
            $table->timestamp('dp_paid_at')->nullable()->after('dp_status');
            $table->string('dp_qris_image')->nullable()->after('dp_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['dp_amount', 'dp_status', 'dp_paid_at', 'dp_qris_image']);
        });
    }
};