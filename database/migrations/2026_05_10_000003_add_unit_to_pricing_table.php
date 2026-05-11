<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing', function (Blueprint $table) {
            $table->string('unit', 16)->default('per_hari')->after('paket_tipe');
        });

        DB::table('pricing')->whereNull('unit')->update(['unit' => 'per_hari']);

        DB::statement("
            DELETE p1 FROM pricing p1
            INNER JOIN pricing p2
                ON p1.vehicle_id = p2.vehicle_id
               AND p1.kota = p2.kota
               AND p1.paket_tipe = p2.paket_tipe
               AND p1.unit = p2.unit
               AND p1.id > p2.id
        ");

        Schema::table('pricing', function (Blueprint $table) {
            $table->unique(['vehicle_id', 'kota', 'paket_tipe', 'unit'], 'pricing_vehicle_city_type_unit_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pricing', function (Blueprint $table) {
            $table->dropUnique('pricing_vehicle_city_type_unit_unique');
            $table->dropColumn('unit');
        });
    }
};
