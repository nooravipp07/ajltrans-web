<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            // 8.1 Tier Ekonomis
            [
                'nama' => 'Honda Brio',
                'tipe' => 'Hatchback',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 350000,
                'harga_jkt' => 400000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Avanza TSS',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 450000,
                'harga_jkt' => 500000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Veloz Q',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 500000,
                'harga_jkt' => 550000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Hyundai Stargazer Essential',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 500000,
                'harga_jkt' => 550000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Hyundai Stargazer Prime',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 550000,
                'harga_jkt' => 600000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Mitsubishi Xpander',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 550000,
                'harga_jkt' => 600000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Honda City Hatchback',
                'tipe' => 'Hatchback',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 600000,
                'harga_jkt' => 650000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Ford Ranger Double Cabin 4x4',
                'tipe' => 'Pickup',
                'kategori' => ['sewa_mobil', 'travel'],
                'harga_bdg' => 600000,
                'harga_jkt' => 700000,
                'tier' => 'ekonomis',
                'status' => 'tersedia',
            ],

            // 8.2 Tier Mid-Range
            [
                'nama' => 'Toyota Innova Reborn',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 650000,
                'harga_jkt' => 750000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Honda HR-V Panoramic',
                'tipe' => 'SUV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 800000,
                'harga_jkt' => 900000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Zenix G Hybrid',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 900000,
                'harga_jkt' => 1000000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Fortuner GR',
                'tipe' => 'SUV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 1200000,
                'harga_jkt' => 1400000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Hiace Commuter Euro 4',
                'tipe' => 'Van',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 1200000,
                'harga_jkt' => 1400000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Suzuki Jimny 3 Doors',
                'tipe' => 'SUV Off-road',
                'kategori' => ['sewa_mobil'],
                'harga_bdg' => 1200000,
                'harga_jkt' => 1300000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Zenix Q Hybrid',
                'tipe' => 'MPV',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 1200000,
                'harga_jkt' => 1350000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Mitsubishi Pajero Sport Dakar',
                'tipe' => 'SUV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 1300000,
                'harga_jkt' => 1500000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Fortuner GR Sport',
                'tipe' => 'SUV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 1300000,
                'harga_jkt' => 1500000,
                'tier' => 'mid_range',
                'status' => 'tersedia',
            ],

            // 8.3 Tier Premium
            [
                'nama' => 'Toyota Hiace Premio',
                'tipe' => 'Premium Van',
                'kategori' => ['sewa_mobil', 'city_tour', 'travel'],
                'harga_bdg' => 1500000,
                'harga_jkt' => 1700000,
                'tier' => 'premium',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota New Camry',
                'tipe' => 'Sedan',
                'kategori' => ['sewa_mobil'],
                'harga_bdg' => 2000000,
                'harga_jkt' => 2300000,
                'tier' => 'premium',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Alphard Transformer',
                'tipe' => 'Premium MPV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 2500000,
                'harga_jkt' => 2800000,
                'tier' => 'premium',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Alphard Hybrid',
                'tipe' => 'Premium MPV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 3500000,
                'harga_jkt' => 4000000,
                'tier' => 'premium',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'BMW M430i Coupe',
                'tipe' => 'Sport Coupe',
                'kategori' => ['sewa_mobil'],
                'harga_bdg' => 8000000,
                'harga_jkt' => 9000000,
                'tier' => 'premium',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Toyota Landcruiser VRX300',
                'tipe' => 'Luxury SUV',
                'kategori' => ['sewa_mobil', 'city_tour'],
                'harga_bdg' => 15000000,
                'harga_jkt' => 17000000,
                'tier' => 'premium',
                'status' => 'tersedia',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
