<?php

namespace Database\Seeders;

use App\Models\ContentCms;
use Illuminate\Database\Seeder;

class ContentCmsSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            // Branding Section
            [
                'section' => 'branding',
                'key' => 'logo_light',
                'value_id' => '/images/logo-light.png',
                'value_en' => '/images/logo-light.png',
                'type' => 'image',
            ],
            [
                'section' => 'branding',
                'key' => 'logo_dark',
                'value_id' => '/images/logo-dark.png',
                'value_en' => '/images/logo-dark.png',
                'type' => 'image',
            ],
            // Hero Section
            [
                'section' => 'hero',
                'key' => 'title',
                'value_id' => 'Sewa Mobil Premium di Bandung & Jakarta',
                'value_en' => 'Premium Car Rental in Bandung & Jakarta',
                'type' => 'text',
            ],
            [
                'section' => 'hero',
                'key' => 'subtitle',
                'value_id' => 'Nikmati perjalanan mewah dengan armada terbaru dan layanan profesional terbaik dari AJL Trans.',
                'value_en' => 'Enjoy a luxury journey with the latest fleet and the best professional service from AJL Trans.',
                'type' => 'textarea',
            ],
            // Services Section
            [
                'section' => 'services',
                'key' => 'title',
                'value_id' => 'Layanan Kami',
                'value_en' => 'Our Services',
                'type' => 'text',
            ],
            [
                'section' => 'services',
                'key' => 'car_rental_desc',
                'value_id' => 'Sewa mobil harian dengan sopir atau lepas kunci untuk kebutuhan bisnis atau liburan.',
                'value_en' => 'Daily car rental with driver or self-drive for business or vacation needs.',
                'type' => 'textarea',
            ],
            [
                'section' => 'services',
                'key' => 'city_tour_desc',
                'value_id' => 'Paket wisata keliling kota Bandung dan Jakarta dengan destinasi terbaik.',
                'value_en' => 'City tour packages around Bandung and Jakarta with the best destinations.',
                'type' => 'textarea',
            ],
            [
                'section' => 'services',
                'key' => 'travel_desc',
                'value_id' => 'Layanan antar jemput antar kota yang nyaman dan tepat waktu.',
                'value_en' => 'Comfortable and punctual intercity shuttle service.',
                'type' => 'textarea',
            ],
            // About Section
            [
                'section' => 'about',
                'key' => 'title',
                'value_id' => 'Tentang AJL Trans',
                'value_en' => 'About AJL Trans',
                'type' => 'text',
            ],
            [
                'section' => 'about',
                'key' => 'description',
                'value_id' => 'AJL Trans adalah penyedia layanan transportasi premium yang berfokus pada kenyamanan dan keamanan pelanggan. Dengan armada terbaru dan pengemudi berpengalaman, kami siap melayani kebutuhan perjalanan Anda di Bandung dan Jakarta.',
                'value_en' => 'AJL Trans is a premium transportation service provider focusing on customer comfort and safety. With the latest fleet and experienced drivers, we are ready to serve your travel needs in Bandung and Jakarta.',
                'type' => 'textarea',
            ],
            // Social Media Section
            [
                'section' => 'social',
                'key' => 'instagram',
                'value_id' => 'https://instagram.com/ajltrans',
                'value_en' => 'https://instagram.com/ajltrans',
                'type' => 'text',
            ],
            [
                'section' => 'social',
                'key' => 'facebook',
                'value_id' => 'https://facebook.com/ajltrans',
                'value_en' => 'https://facebook.com/ajltrans',
                'type' => 'text',
            ],
            [
                'section' => 'social',
                'key' => 'tiktok',
                'value_id' => 'https://tiktok.com/@ajltrans',
                'value_en' => 'https://tiktok.com/@ajltrans',
                'type' => 'text',
            ],
            [
                'section' => 'social',
                'key' => 'youtube',
                'value_id' => 'https://youtube.com/@ajltrans',
                'value_en' => 'https://youtube.com/@ajltrans',
                'type' => 'text',
            ],
            // Payment Section
            [
                'section' => 'payment',
                'key' => 'qris_image',
                'value_id' => null,
                'value_en' => null,
                'type' => 'image',
            ],
            [
                'section' => 'payment',
                'key' => 'dp_info_title',
                'value_id' => 'Pembayaran DP',
                'value_en' => 'Down Payment',
                'type' => 'text',
            ],
            [
                'section' => 'payment',
                'key' => 'dp_info_desc',
                'value_id' => 'Silakan lakukan pembayaran DP untuk melanjutkan proses booking. DP akan dikonfirmasi oleh admin dalam waktu 1x24 jam.',
                'value_en' => 'Please make a down payment to continue the booking process. DP will be confirmed by admin within 24 hours.',
                'type' => 'textarea',
            ],
        ];

        foreach ($contents as $content) {
            ContentCms::updateOrCreate(
                ['section' => $content['section'], 'key' => $content['key']],
                $content
            );
        }
    }
}
