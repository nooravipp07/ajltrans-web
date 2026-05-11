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
        Schema::create('content_cms', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // hero, navbar, about, stats, etc
            $table->string('key');
            $table->text('value_id')->nullable();
            $table->text('value_en')->nullable();
            $table->string('type')->default('text'); // text, image, number, url
            $table->timestamps();
            $table->unique(['section','key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_cms');
    }
};
