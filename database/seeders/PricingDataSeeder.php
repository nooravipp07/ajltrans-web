<?php

namespace Database\Seeders;

use App\Models\Pricing;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing pricing data
        DB::table('pricing')->truncate();

        $pricingData = [
            // Format: 'Nama Kendaraan' => [ 'bandung' => [lepas, city, luar, travel], 'jakarta' => [lepas, city, luar, travel] ]
            'Toyota Haice Commuter 14 Seat' => [
                'bandung' => [700000, 1200000, 1500000, 1800000],
                'jakarta' => [1000000, 1500000, 1800000, 1800000],
            ],
            'Toyota Haice Commuter Euro 4 14 Seat' => [
                'bandung' => [800000, 1300000, 1700000, 2000000],
                'jakarta' => [1200000, 1700000, 1900000, 2000000],
            ],
            'Toyota Haice Premio 14 Seat' => [
                'bandung' => [1000000, 1700000, 2000000, 2500000],
                'jakarta' => [1500000, 2000000, 2300000, 2500000],
            ],
            'Toyota Haice Premio Luxury 9 Seat' => [
                'bandung' => [1500000, 2300000, 2500000, 3000000],
                'jakarta' => [2000000, 2500000, 2800000, 3000000],
            ],
            'Toyota Haice Premio Luxury 8 Seat' => [
                'bandung' => [3000000, 3500000, 4000000, 3500000],
                'jakarta' => [3000000, 4000000, 4300000, 3500000],
            ],
            'Toyota Innova Reborn' => [
                'bandung' => [650000, 1100000, 1500000, 1500000],
                'jakarta' => [750000, 1250000, 1500000, 1500000],
            ],
            'Toyota Innova Zenix Type G Non Hybrid' => [
                'bandung' => [800000, 1250000, 1750000, 1750000],
                'jakarta' => [900000, 1400000, 1800000, 1750000],
            ],
            'Toyota Innova Zenix Type G Hybrid' => [
                'bandung' => [900000, 1500000, 1900000, 1900000],
                'jakarta' => [1100000, 1600000, 1800000, 1900000],
            ],
            'Toyota Innova Zenix Type Q Modellista' => [
                'bandung' => [1200000, 1700000, 2200000, 2200000],
                'jakarta' => [1300000, 1800000, 2200000, 2200000],
            ],
            'Toyota Alphard Gen 3' => [
                'bandung' => [2000000, 2500000, 3000000, 3000000],
                'jakarta' => [2000000, 3500000, 4000000, 4000000],
            ],
            'Toyota Alphard Gen 4 Hybrid' => [
                'bandung' => [3000000, 3500000, 4000000, 4000000],
                'jakarta' => [3000000, 4000000, 5000000, 5000000],
            ],
            'Toyota Fortuner GR Sport' => [
                'bandung' => [1350000, 1850000, 2350000, 2350000],
                'jakarta' => [1450000, 1950000, 2350000, 2350000],
            ],
            'Toyota Fortuner GR' => [
                'bandung' => [1250000, 1750000, 2500000, 2500000],
                'jakarta' => [1350000, 1850000, 2500000, 2500000],
            ],
            'Toyota New Camry' => [
                'bandung' => [2100000, 2600000, 3100000, 3100000],
                'jakarta' => [2300000, 2800000, 3100000, 3100000],
            ],
            'Toyota Ranger Double Cabin 4x4' => [
                'bandung' => [700000, 1200000, 1700000, 1700000],
                'jakarta' => [800000, 1300000, 1700000, 1700000],
            ],
            'Toyota Avanza TSS' => [
                'bandung' => [450000, 950000, 1450000, 1450000],
                'jakarta' => [550000, 1050000, 1450000, 1450000],
            ],
            'Toyota Avanza NEW' => [
                'bandung' => [350000, 850000, 1350000, 1350000],
                'jakarta' => [450000, 950000, 1350000, 1350000],
            ],
            'Toyota Veloz Type Q' => [
                'bandung' => [550000, 1050000, 1550000, 1550000],
                'jakarta' => [650000, 1150000, 1550000, 1550000],
            ],
            'Toyota Calya' => [
                'bandung' => [350000, 850000, 1350000, 1350000],
                'jakarta' => [350000, 850000, 1350000, 1350000],
            ],
            'Mitsubishi Pajero Sport Dakar' => [
                'bandung' => [1350000, 1850000, 2350000, 2350000],
                'jakarta' => [1500000, 2000000, 2350000, 2350000],
            ],
            'Mitsubishi Xpander' => [
                'bandung' => [550000, 1050000, 1550000, 1550000],
                'jakarta' => [650000, 1500000, 1550000, 1550000],
            ],
            'Honda HR-V Panoramic' => [
                'bandung' => [850000, 1350000, 1850000, 1850000],
                'jakarta' => [1000000, 1500000, 1850000, 1850000],
            ],
            'Honda CRV Prestige' => [
                'bandung' => [1300000, 1800000, 2300000, 2300000],
                'jakarta' => [1500000, 2000000, 2300000, 2300000],
            ],
            'Honda Brio' => [
                'bandung' => [350000, 850000, 1350000, 1350000],
                'jakarta' => [400000, 900000, 1350000, 1350000],
            ],
            'Hyundai Stargazer Essential' => [
                'bandung' => [550000, 1050000, 1550000, 1550000],
                'jakarta' => [650000, 1150000, 1550000, 1550000],
            ],
            'Hyundai Stargazer Prime' => [
                'bandung' => [600000, 1100000, 1600000, 1600000],
                'jakarta' => [700000, 1200000, 1600000, 1600000],
            ],
            'Wullings Confero' => [
                'bandung' => [350000, 850000, 1350000, 1350000],
                'jakarta' => [500000, 1000000, 1350000, 1350000],
            ],
            'Wullings EV' => [
                'bandung' => [450000, 750000, 1250000, 1250000],
                'jakarta' => [450000, 950000, 1250000, 1250000],
            ],
            'Isuzu ELF Long 19 Seat' => [
                'bandung' => [1000000, 1500000, 2000000, 2000000],
                'jakarta' => [1300000, 1800000, 2000000, 2000000],
            ],
            'Isuzu ELF Long 22 Seat' => [
                'bandung' => [1000000, 1600000, 2300000, 2300000],
                'jakarta' => [1500000, 1900000, 2300000, 2300000],
            ],
            'Coaster ELF 18 Seat' => [
                'bandung' => [1000000, 1700000, 2200000, 2200000],
                'jakarta' => [1700000, 1800000, 2200000, 2200000],
            ],
            'Coaster ELF 22 Seat' => [
                'bandung' => [1200000, 1800000, 2300000, 2300000],
                'jakarta' => [1800000, 1900000, 2300000, 2300000],
            ],
            'Medium BUS 30 Seat' => [
                'bandung' => [1500000, 2500000, 3000000, 3000000],
                'jakarta' => [1500000, 3000000, 3000000, 3000000],
            ],
            'Medium BUS 35 Seat' => [
                'bandung' => [2000000, 3000000, 3500000, 3500000],
                'jakarta' => [2000000, 3500000, 3500000, 3500000],
            ],
            'BIG BUS 50 Seat' => [
                'bandung' => [2000000, 4500000, 5000000, 5000000],
                'jakarta' => [2000000, 4000000, 5000000, 5000000],
            ],
            'BIG BUS 60 Seat' => [
                'bandung' => [3000000, 5000000, 5500000, 5500000],
                'jakarta' => [3000000, 5000000, 5500000, 5500000],
            ],
        ];

        $categories = ['lepas_kunci', 'city_tour_allin', 'luar_kota_allin', 'travel_bandara'];

        foreach ($pricingData as $vehicleName => $citiesData) {
            $vehicle = Vehicle::where('nama', 'LIKE', '%' . $vehicleName . '%')->first();
            
            if (!$vehicle) {
                // If not found, try to create or log
                $this->command->warn("Vehicle not found: $vehicleName");
                continue;
            }

            foreach ($citiesData as $city => $prices) {
                foreach ($categories as $index => $category) {
                    Pricing::create([
                        'vehicle_id' => $vehicle->id,
                        'kota' => $city,
                        'paket_tipe' => $category,
                        'harga_dasar' => $prices[$index],
                    ]);
                }
            }
        }
    }
}
