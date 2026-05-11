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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kota');
            $table->string('kendaraan_disewa');
            $table->text('ulasan_id');
            $table->text('ulasan_en');
            $table->tinyInteger('rating')->default(5);
            $table->string('foto_avatar')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved'); // Default approved for seeder data
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
        Schema::dropIfExists('testimonials');
    }
};
