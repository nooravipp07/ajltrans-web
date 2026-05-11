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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tipe'); // MPV, SUV, Sedan, Premium MPV
            $table->json('kategori'); // ["sewa_mobil","city_tour","travel"]
            $table->unsignedInteger('harga_bdg')->nullable();
            $table->unsignedInteger('harga_jkt')->nullable();
            $table->json('foto_urls')->nullable();
            $table->enum('status', ['tersedia','tidak_tersedia','perawatan']);
            $table->enum('badge', ['populer','baru','promo','none'])->default('none');
            $table->enum('tier', ['ekonomis','mid_range','premium']);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
