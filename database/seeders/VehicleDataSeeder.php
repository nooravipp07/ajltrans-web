<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleDataSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['nama' => 'Toyota Haice Commuter 14 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Haice Commuter Euro 4 14 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Haice Commuter Euro14 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Haice Premio 14 Seat', 'tipe' => 'Premium Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Toyota Haice Premio Luxury 9 Seat', 'tipe' => 'Luxury Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Toyota Haice Premio Luxury 8 Seat', 'tipe' => 'Luxury Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Toyota Haice Premio Luxury 8 Seat VIP', 'tipe' => 'Luxury Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Toyota Innova Reborn', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Innova Zenix Type G Non Hybrid', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Innova Zenix Type G Hybrid', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Innova Zenix Type Q Modellista', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Toyota Alphard Gen 3', 'tipe' => 'Premium MPV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'premium'],
            ['nama' => 'Toyota Alphard Gen 4 Hybrid', 'tipe' => 'Premium MPV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'premium'],
            ['nama' => 'Toyota Fortuner GR Sport', 'tipe' => 'SUV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota Fortuner GR', 'tipe' => 'SUV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'mid_range'],
            ['nama' => 'Toyota New Camry', 'tipe' => 'Sedan', 'kategori' => ['sewa_mobil'], 'tier' => 'premium'],
            ['nama' => 'Toyota Ranger Double Cabin 4X4', 'tipe' => 'Pickup', 'kategori' => ['sewa_mobil', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Toyota Avanza TSS', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Toyota Avanza NEW', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Toyota Veloz Type Q', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Toyota Calya', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Mitsubishi Pajero Sport Dakar', 'tipe' => 'SUV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'mid_range'],
            ['nama' => 'Mitsubishi Xpander', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Honda HR-V Panoramic', 'tipe' => 'SUV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'mid_range'],
            ['nama' => 'Honda CRV Prestige', 'tipe' => 'SUV', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'mid_range'],
            ['nama' => 'Honda Brio', 'tipe' => 'Hatchback', 'kategori' => ['sewa_mobil', 'city_tour'], 'tier' => 'ekonomis'],
            ['nama' => 'Hyundai Stargazer Essential', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Hyundai Stargazer Prime', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Wullings Confero', 'tipe' => 'MPV', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Wullings EV', 'tipe' => 'Electric', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'ekonomis'],
            ['nama' => 'Isuzu ELF Long 19 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Isusu ELF Long 19 Seat GIGA', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Isuzu ELF Long 22 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Coaster ELF 18 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Coaster ELF 18 Seat JB 5', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Coaster ELF 22 Seat', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Coaster ELF 22 Seat JB 5', 'tipe' => 'Van', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'mid_range'],
            ['nama' => 'Medium BUS 30 Seat', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Medium BUS 35 Seat', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'Medium BUS 31-35 Seat JB 5', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'BIG BUS 50 Seat', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'BIG BUS 60 Seat', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'BIG BUS 47-50 Seat JB5', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
            ['nama' => 'BIG BUS HDD 50-59 Seat JB5', 'tipe' => 'Bus', 'vehicle_size' => 'besar', 'kategori' => ['sewa_mobil', 'city_tour', 'travel'], 'tier' => 'premium'],
        ];

        foreach ($vehicles as $v) {
            Vehicle::updateOrCreate(['nama' => $v['nama']], [
                'tipe' => $v['tipe'],
                'vehicle_size' => $v['vehicle_size'] ?? 'kecil',
                'kategori' => $v['kategori'],
                'tier' => $v['tier'],
                'is_active' => true,
                'status' => 'tersedia',
            ]);
        }
    }
}
