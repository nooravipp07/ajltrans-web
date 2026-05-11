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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // AJL-2026-XXXX
            $table->string('customer_nik', 16);
            $table->foreign('customer_nik')->references('nik')->on('customers');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->enum('kategori', ['sewa_mobil','city_tour','travel']);
            $table->enum('kota', ['bandung','jakarta']);
            $table->date('tanggal_mulai');
            $table->integer('durasi')->nullable(); // per 12 jam (sewa_mobil)
            $table->integer('durasi_hari')->nullable(); // per hari (city_tour)
            $table->text('alamat_jemput')->nullable(); // travel
            $table->text('alamat_tujuan')->nullable(); // travel
            $table->unsignedInteger('harga_per_unit');
            $table->unsignedInteger('total_harga');
            $table->enum('status', [
                'menunggu_konfirmasi','dikonfirmasi',
                'sedang_berjalan','selesai','dibatalkan'
            ])->default('menunggu_konfirmasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            $table->index(['kota','kategori','status','tanggal_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
