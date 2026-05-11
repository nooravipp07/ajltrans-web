<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'nama' => 'Budi Santoso',
                'kota' => 'Jakarta',
                'kendaraan_disewa' => 'Toyota Alphard Transformer',
                'ulasan_id' => 'Pelayanan sangat memuaskan, mobil bersih dan wangi. Driver sangat profesional.',
                'ulasan_en' => 'Service was very satisfying, the car was clean and fresh. The driver was very professional.',
                'rating' => 5,
            ],
            [
                'nama' => 'Siska Amelia',
                'kota' => 'Bandung',
                'kendaraan_disewa' => 'Honda HR-V Panoramic',
                'ulasan_id' => 'Sewa lepas kunci prosesnya cepat dan mudah. Kondisi mobil sangat prima untuk jalan-jalan di Bandung.',
                'ulasan_en' => 'Self-drive rental process was fast and easy. The car condition was excellent for touring Bandung.',
                'rating' => 5,
            ],
            [
                'nama' => 'Randi Wijaya',
                'kota' => 'Surabaya',
                'kendaraan_disewa' => 'Toyota Hiace Premio',
                'ulasan_id' => 'Cocok banget buat perjalanan keluarga besar. Kabin luas dan nyaman sekali.',
                'ulasan_en' => 'Perfect for large family trips. The cabin is spacious and very comfortable.',
                'rating' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
