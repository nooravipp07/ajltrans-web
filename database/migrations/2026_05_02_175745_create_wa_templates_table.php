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
        Schema::create('wa_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['sewa_mobil','city_tour','travel']);
            $table->text('template_id'); // Bahasa Indonesia
            $table->text('template_en'); // English
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_templates');
    }
};
