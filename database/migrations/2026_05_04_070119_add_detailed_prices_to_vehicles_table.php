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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedInteger('harga_lepas_kunci_bdg')->default(0)->after('harga_jkt');
            $table->unsignedInteger('harga_lepas_kunci_jkt')->default(0)->after('harga_lepas_kunci_bdg');
            $table->unsignedInteger('harga_city_tour_allin_bdg')->default(0)->after('harga_lepas_kunci_jkt');
            $table->unsignedInteger('harga_city_tour_allin_jkt')->default(0)->after('harga_city_tour_allin_bdg');
            $table->unsignedInteger('harga_luar_kota_allin_bdg')->default(0)->after('harga_city_tour_allin_jkt');
            $table->unsignedInteger('harga_luar_kota_allin_jkt')->default(0)->after('harga_luar_kota_allin_bdg');
            $table->unsignedInteger('harga_travel_bandara_bdg')->default(0)->after('harga_luar_kota_allin_jkt');
            $table->unsignedInteger('harga_travel_bandara_jkt')->default(0)->after('harga_travel_bandara_bdg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'harga_lepas_kunci_bdg',
                'harga_lepas_kunci_jkt',
                'harga_city_tour_allin_bdg',
                'harga_city_tour_allin_jkt',
                'harga_luar_kota_allin_bdg',
                'harga_luar_kota_allin_jkt',
                'harga_travel_bandara_bdg',
                'harga_travel_bandara_jkt',
            ]);
        });
    }
};
