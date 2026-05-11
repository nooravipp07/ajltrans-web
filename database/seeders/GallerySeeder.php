<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('galleries')->truncate();

        $items = [
            [
                'title_id' => 'Perjalanan Wisata Bandung',
                'title_en' => 'Bandung City Tour Journey',
                'type' => 'photo',
                'url' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title_id' => 'Armada Premium AJL Trans',
                'title_en' => 'AJL Trans Premium Fleet',
                'type' => 'photo',
                'url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title_id' => 'Layanan Travel Bandara',
                'title_en' => 'Airport Transfer Service',
                'type' => 'video',
                'url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title_id' => 'Kenyamanan Interior Hiace',
                'title_en' => 'Hiace Interior Comfort',
                'type' => 'photo',
                'url' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title_id' => 'Perjalanan Bisnis Jakarta',
                'title_en' => 'Jakarta Business Trip',
                'type' => 'photo',
                'url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title_id' => 'Wisata Keluarga dengan Alphard',
                'title_en' => 'Family Trip with Alphard',
                'type' => 'video',
                'url' => 'https://www.w3schools.com/html/movie.mp4',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'title_id' => 'Sewa Mobil Lepas Kunci',
                'title_en' => 'Self Drive Car Rental',
                'type' => 'photo',
                'url' => 'https://images.unsplash.com/photo-1494905998402-395d579af36f?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'title_id' => 'Armada Bus Pariwisata',
                'title_en' => 'Tourism Bus Fleet',
                'type' => 'photo',
                'url' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($items as $item) {
            Gallery::create($item);
        }
    }
}
