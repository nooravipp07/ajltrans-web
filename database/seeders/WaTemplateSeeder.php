<?php

namespace Database\Seeders;

use App\Models\WaTemplate;
use Illuminate\Database\Seeder;

class WaTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'kategori' => 'sewa_mobil',
                'template_id' => "Halo AJL Trans! 👋\n\nSaya ingin melakukan pemesanan:\n📋 Kode Booking : {{BOOKING_CODE}}\n👤 Nama         : {{NAMA}}\n🚗 Kendaraan    : {{KENDARAAN}}\n📍 Kota         : {{KOTA}}\n📅 Tanggal Mulai: {{TGL_MULAI}}\n⏱️  Durasi       : {{DURASI}} × 12 jam\n💰 Harga        : {{HARGA}} / 12 jam\n💳 Total        : {{TOTAL}}\n\nMohon konfirmasi ketersediaan. Terima kasih!",
                'template_en' => "Hello AJL Trans! 👋\n\nI would like to make a reservation:\n📋 Booking Code  : {{BOOKING_CODE}}\n👤 Name          : {{NAMA}}\n🚗 Vehicle       : {{KENDARAAN}}\n📍 City          : {{KOTA}}\n📅 Start Date    : {{TGL_MULAI}}\n⏱️  Duration      : {{DURASI}} × 12 hours\n💰 Rate          : {{HARGA}} / 12 hours\n💳 Total         : {{TOTAL}}\n\nPlease confirm availability. Thank you!",
            ],
            [
                'kategori' => 'city_tour',
                'template_id' => "Halo AJL Trans! 👋\n\nPemesanan City Tour:\n📋 Kode Booking : {{BOOKING_CODE}}\n👤 Nama         : {{NAMA}}\n🚗 Kendaraan    : {{KENDARAAN}}\n📍 Kota         : {{KOTA}}\n📅 Tanggal Mulai: {{TGL_MULAI}}\n📆 Durasi       : {{DURASI}} hari\n💰 Harga        : {{HARGA}} / hari\n💳 Total        : {{TOTAL}}\n\nMohon konfirmasi ketersediaan. Terima kasih!",
                'template_en' => "Hello AJL Trans! 👋\n\nCity Tour Reservation:\n📋 Booking Code : {{BOOKING_CODE}}\n👤 Name         : {{NAMA}}\n🚗 Vehicle       : {{KENDARAAN}}\n📍 City          : {{KOTA}}\n📅 Start Date    : {{TGL_MULAI}}\n📆 Duration       : {{DURASI}} days\n💰 Rate        : {{HARGA}} / day\n💳 Total       : {{TOTAL}}\n\nPlease confirm availability. Thank you!",
            ],
            [
                'kategori' => 'travel',
                'template_id' => "Halo AJL Trans! 👋\n\nPemesanan Travel:\n📋 Kode Booking : {{BOOKING_CODE}}\n👤 Nama         : {{NAMA}}\n🚗 Kendaraan    : {{KENDARAAN}}\n📍 Kota         : {{KOTA}}\n📅 Tanggal     : {{TGL_MULAI}}\n📌 Penjemputan : {{ALAMAT_JEMPUT}}\n🏁 Tujuan      : {{ALAMAT_TUJUAN}}\n💰 Harga       : {{HARGA}}\n💳 Total       : {{TOTAL}}\n\nMohon konfirmasi. Terima kasih!",
                'template_en' => "Hello AJL Trans! 👋\n\nTravel Reservation:\n📋 Booking Code : {{BOOKING_CODE}}\n👤 Name         : {{NAMA}}\n🚗 Vehicle       : {{KENDARAAN}}\n📍 City          : {{KOTA}}\n📅 Date     : {{TGL_MULAI}}\n📌 Pickup Location : {{ALAMAT_JEMPUT}}\n🏁 Destination      : {{ALAMAT_TUJUAN}}\n💰 Rate       : {{HARGA}}\n💳 Total       : {{TOTAL}}\n\nPlease confirm. Thank you!",
            ],
        ];

        foreach ($templates as $template) {
            WaTemplate::create($template);
        }
    }
}
