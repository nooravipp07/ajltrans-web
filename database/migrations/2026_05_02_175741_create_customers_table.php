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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->index();
            $table->string('nama');
            $table->text('alamat');
            $table->string('no_wa', 20);
            $table->string('foto_identitas')->nullable();
            $table->enum('status', ['aktif', 'blacklist'])->default('aktif');
            $table->text('blacklist_reason')->nullable();
            $table->timestamps();
            $table->index(['nama', 'no_wa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
