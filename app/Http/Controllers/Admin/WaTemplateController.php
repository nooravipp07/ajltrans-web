<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentCms;
use App\Models\WaTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WaTemplateController extends Controller
{
    public function index()
    {
        ContentCms::firstOrCreate(
            ['section' => 'settings', 'key' => 'whatsapp_number'],
            [
                'value_id' => config('ajl.whatsapp_number', '62895377304295'),
                'value_en' => config('ajl.whatsapp_number', '62895377304295'),
                'type' => 'text',
            ]
        );

        $column = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'wa_templates')
            ->where('COLUMN_NAME', 'kategori')
            ->select(['COLUMN_TYPE'])
            ->first();

        $columnType = strtolower((string) ($column->COLUMN_TYPE ?? ''));
        if ($columnType !== '' && !str_contains($columnType, 'enum(')) {
            WaTemplate::firstOrCreate(
                ['kategori' => 'dp_confirmation_sewa_mobil'],
                [
                    'template_id' => "Halo AJL Trans! 👋\n\nSaya ingin konfirmasi pembayaran DP untuk booking:\n📋 Kode Booking   : {{BOOKING_CODE}}\n👤 Nama           : {{NAMA}}\n🚗 Kendaraan      : {{KENDARAAN}}\n🧾 Jenis Kendaraan: {{TIPE_KENDARAAN}}\n🧾 Jenis Layanan  : {{SERVICE_TYPE}}\n📍 Kota           : {{KOTA}}\n📅 Tanggal Mulai  : {{TGL_MULAI}}\n⏱️  Durasi         : {{DURASI}}\n💰 Total Sewa      : {{TOTAL_FULL}}\n💳 DP Dibayar     : {{DP}}\n💰 Sisa Tagihan   : {{SISA}}\n🧾 Bukti Bayar    : {{BUKTI_DP_URL}}\n\nMohon diproses ya. Terima kasih!",
                    'template_en' => "Hello AJL Trans! 👋\n\nI would like to confirm the down payment for booking:\n📋 Booking Code   : {{BOOKING_CODE}}\n👤 Name           : {{NAMA}}\n🚗 Vehicle        : {{KENDARAAN}}\n🧾 Vehicle Type   : {{TIPE_KENDARAAN}}\n🧾 Service Type   : {{SERVICE_TYPE}}\n📍 City           : {{KOTA}}\n📅 Start Date     : {{TGL_MULAI}}\n⏱️  Duration       : {{DURASI}}\n💰 Total          : {{TOTAL_FULL}}\n💳 DP Paid        : {{DP}}\n💰 Remaining      : {{SISA}}\n🧾 Proof          : {{BUKTI_DP_URL}}\n\nPlease process it. Thank you!",
                ]
            );

            WaTemplate::firstOrCreate(
                ['kategori' => 'dp_confirmation_city_tour'],
                [
                    'template_id' => "Halo AJL Trans! 👋\n\nKonfirmasi pembayaran DP City Tour:\n📋 Kode Booking   : {{BOOKING_CODE}}\n👤 Nama           : {{NAMA}}\n🚗 Kendaraan      : {{KENDARAAN}}\n🧾 Jenis Kendaraan: {{TIPE_KENDARAAN}}\n🧾 Jenis Layanan  : {{SERVICE_TYPE}}\n📍 Kota           : {{KOTA}}\n📅 Tanggal Mulai  : {{TGL_MULAI}}\n📆 Durasi         : {{DURASI}}\n💰 Total Sewa      : {{TOTAL_FULL}}\n💳 DP Dibayar     : {{DP}}\n💰 Sisa Tagihan   : {{SISA}}\n🧾 Bukti Bayar    : {{BUKTI_DP_URL}}\n\nMohon diproses ya. Terima kasih!",
                    'template_en' => "Hello AJL Trans! 👋\n\nDP confirmation for City Tour:\n📋 Booking Code   : {{BOOKING_CODE}}\n👤 Name           : {{NAMA}}\n🚗 Vehicle        : {{KENDARAAN}}\n🧾 Vehicle Type   : {{TIPE_KENDARAAN}}\n🧾 Service Type   : {{SERVICE_TYPE}}\n📍 City           : {{KOTA}}\n📅 Start Date     : {{TGL_MULAI}}\n📆 Duration       : {{DURASI}}\n💰 Total          : {{TOTAL_FULL}}\n💳 DP Paid        : {{DP}}\n💰 Remaining      : {{SISA}}\n🧾 Proof          : {{BUKTI_DP_URL}}\n\nPlease process it. Thank you!",
                ]
            );

            WaTemplate::firstOrCreate(
                ['kategori' => 'dp_confirmation_travel'],
                [
                    'template_id' => "Halo AJL Trans! 👋\n\nKonfirmasi pembayaran DP Travel:\n📋 Kode Booking   : {{BOOKING_CODE}}\n👤 Nama           : {{NAMA}}\n🚗 Kendaraan      : {{KENDARAAN}}\n🧾 Jenis Kendaraan: {{TIPE_KENDARAAN}}\n🧾 Jenis Layanan  : {{SERVICE_TYPE}}\n📍 Kota           : {{KOTA}}\n📅 Tanggal        : {{TGL_MULAI}}\n⏱️  Durasi         : {{DURASI}}\n📌 Jemput         : {{ALAMAT_JEMPUT}}\n🏁 Tujuan         : {{ALAMAT_TUJUAN}}\n💰 Total Sewa      : {{TOTAL_FULL}}\n💳 DP Dibayar     : {{DP}}\n💰 Sisa Tagihan   : {{SISA}}\n🧾 Bukti Bayar    : {{BUKTI_DP_URL}}\n\nMohon diproses ya. Terima kasih!",
                    'template_en' => "Hello AJL Trans! 👋\n\nDP confirmation for Travel:\n📋 Booking Code   : {{BOOKING_CODE}}\n👤 Name           : {{NAMA}}\n🚗 Vehicle        : {{KENDARAAN}}\n🧾 Vehicle Type   : {{TIPE_KENDARAAN}}\n🧾 Service Type   : {{SERVICE_TYPE}}\n📍 City           : {{KOTA}}\n📅 Date           : {{TGL_MULAI}}\n⏱️  Duration       : {{DURASI}}\n📌 Pickup         : {{ALAMAT_JEMPUT}}\n🏁 Destination    : {{ALAMAT_TUJUAN}}\n💰 Total          : {{TOTAL_FULL}}\n💳 DP Paid        : {{DP}}\n💰 Remaining      : {{SISA}}\n🧾 Proof          : {{BUKTI_DP_URL}}\n\nPlease process it. Thank you!",
                ]
            );
        }

        return Inertia::render('Admin/WaTemplates/Index', [
            'templates' => WaTemplate::orderBy('kategori')->get(),
            'whatsapp_number' => ContentCms::where('section', 'settings')->where('key', 'whatsapp_number')->value('value_id'),
        ]);
    }

    public function updateWhatsappNumber(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => 'required|string|max:32',
        ]);

        $raw = preg_replace('/[^0-9]/', '', (string) $validated['whatsapp_number']);
        if (str_starts_with($raw, '0')) {
            $raw = '62' . substr($raw, 1);
        }

        ContentCms::updateOrCreate(
            ['section' => 'settings', 'key' => 'whatsapp_number'],
            [
                'value_id' => $raw,
                'value_en' => $raw,
                'type' => 'text',
            ]
        );

        return back()->with('success', 'Nomor WhatsApp berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $template = WaTemplate::findOrFail($id);
        
        $validated = $request->validate([
            'template_id' => 'required|string',
            'template_en' => 'required|string',
        ]);

        $template->update($validated);

        return back()->with('success', 'Template WA berhasil diperbarui.');
    }
}
