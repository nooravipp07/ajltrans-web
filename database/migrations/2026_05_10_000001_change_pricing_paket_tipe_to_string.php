<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `pricing` MODIFY `paket_tipe` VARCHAR(64) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `pricing` MODIFY `paket_tipe` ENUM('lepas_kunci', 'city_tour_allin', 'luar_kota_allin', 'travel_bandara') NOT NULL");
    }
};

