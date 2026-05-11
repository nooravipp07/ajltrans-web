<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `wa_templates` MODIFY `kategori` VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `wa_templates` MODIFY `kategori` ENUM('sewa_mobil','city_tour','travel') NOT NULL");
    }
};

