<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ContentCms;
use App\Models\WaTemplate;

class WhatsAppService
{
    public function generateLink(Booking $booking, ?string $templateCategory = null): string
    {
        $category = $templateCategory ?? $booking->kategori;
        $template = WaTemplate::where('kategori', $category)->first();
        
        if (!$template) {
            $tpl = str_starts_with($category, 'dp_confirmation')
                ? "Halo AJL Trans, saya ingin mengonfirmasi pembayaran DP untuk booking {{BOOKING_CODE}}."
                : "Halo AJL Trans, saya ingin melakukan pemesanan.\n\n- *Kode Booking*: {{BOOKING_CODE}}\n- *Nama*: {{NAMA}}\n- *Kendaraan*: {{KENDARAAN}}\n- *Kategori*: {{KATEGORI}}\n- *Layanan*: {{SERVICE_TYPE}}\n- *Kota*: {{KOTA}}\n- *Tanggal*: {{TGL_RANGE}}\n- *Lama Sewa*: {{DURASI}}\n- *DP*: {{DP}}\n- *Sisa Tagihan*: {{SISA}}\n- *Total Harga*: {{TOTAL_FULL}}\n{{DRIVER_INFO}}";
        } else {
            $lang = app()->getLocale(); // 'id' or 'en' 
            $tpl = $lang === 'en' ? $template->template_en : $template->template_id;
        }

        $message = $this->fillTemplate($tpl, $booking, $category);
        $noWa = ContentCms::where('section', 'settings')
            ->where('key', 'whatsapp_number')
            ->value('value_id') ?? config('ajl.whatsapp_number', '62895377304295');

        $phone = preg_replace('/[^0-9]/', '', (string) $noWa);
        return "https://api.whatsapp.com/send?phone={$phone}&text=" . urlencode($message);
    }

    private function fillTemplate(string $tpl, Booking $booking, string $category): string
    {
        $startDate = $booking->tanggal_mulai;
        $startStr = $startDate ? $startDate->format('d/m/Y') : '-';

        $durasiStr = '-';
        $durasiJam = (int) ($booking->durasi ?? 0);
        $durasiHari = (int) ($booking->durasi_hari ?? 0);
        $durasiTrip = (int) ($booking->durasi_hari ?? 0);

        if ($booking->kategori === 'sewa_mobil') {
            if ($booking->durasi !== null) {
                $durasiStr = $durasiJam . ' Jam';
            } else {
                $durasiStr = $durasiHari . ' Hari';
            }
        } elseif ($booking->kategori === 'travel') {
            $durasiStr = $durasiTrip . ' Trip';
        } else {
            $durasiStr = $durasiHari . ' Hari';
        }

        $endStr = $startStr;
        if ($startDate && $booking->kategori !== 'travel') {
            $days = 1;
            if ($booking->kategori === 'sewa_mobil' && $booking->durasi === null) {
                $days = max($durasiHari, 1);
            } elseif ($booking->kategori === 'city_tour') {
                $days = max($durasiHari, 1);
            }
            $endDate = $startDate->copy()->addDays($days - 1);
            $endStr = $endDate->format('d/m/Y');
        }

        $tglRangeStr = $startStr === '-' ? '-' : ($endStr === $startStr ? $startStr : ($startStr . ' - ' . $endStr));

        $driverInfo = '';
        if ($booking->catatan_admin && str_contains($booking->catatan_admin, 'Sopir')) {
            $driverInfo = "\n- *Layanan Tambahan*: Dengan Sopir Profesional (Konfirmasi biaya di WA)";
        }

        $luarKotaInfo = '';
        if (str_contains(strtolower((string) $booking->service_type), 'luar_kota') && $booking->alamat_tujuan) {
            $luarKotaInfo = "\n- *Tujuan Luar Kota*: " . $booking->alamat_tujuan;
        }

        $dp = (int) ($booking->dp_amount ?? 0);
        $totalFull = (int) ($booking->total_harga ?? 0);
        $sisa = max($totalFull - $dp, 0);

        $isDpConfirmation = str_starts_with($category, 'dp_confirmation');
        $totalForMessage = $isDpConfirmation ? $sisa : $totalFull;

        $proofUrl = '-';
        if ($booking->dp_qris_image) {
            $proofPath = (string) $booking->dp_qris_image;
            $relative = ltrim($proofPath, '/');
            if (!str_starts_with($relative, 'storage/')) {
                $relative = 'storage/' . ltrim($relative, '/');
            }
            $proofUrl = \Illuminate\Support\Facades\URL::to($relative);
        }

        $replacements = [
            '{{BOOKING_CODE}}' => $booking->booking_code,
            '{{NAMA}}' => $booking->customer->nama,
            '{{KENDARAAN}}' => $booking->vehicle->nama,
            '{{TIPE_KENDARAAN}}' => $booking->vehicle->tipe ?? '-',
            '{{KOTA}}' => ucfirst($booking->kota),
            '{{KATEGORI}}' => ucfirst(str_replace('_', ' ', $booking->kategori)),
            '{{SERVICE_TYPE}}' => ucfirst(str_replace('_', ' ', $booking->service_type)),
            '{{TGL_MULAI}}' => $booking->tanggal_mulai->format('d/m/Y'),
            '{{TGL_SELESAI}}' => $endStr,
            '{{TGL_RANGE}}' => $tglRangeStr,
            '{{DURASI}}' => $durasiStr,
            '{{DURASI_JAM}}' => (string) $durasiJam,
            '{{DURASI_HARI}}' => (string) $durasiHari,
            '{{DURASI_TRIP}}' => (string) $durasiTrip,
            '{{HARGA}}' => 'Rp ' . number_format($booking->harga_per_unit, 0, ',', '.'),
            '{{DP}}' => 'Rp ' . number_format($dp, 0, ',', '.'),
            '{{SISA}}' => 'Rp ' . number_format($sisa, 0, ',', '.'),
            '{{TOTAL_FULL}}' => 'Rp ' . number_format($totalFull, 0, ',', '.'),
            '{{TOTAL}}' => 'Rp ' . number_format($totalForMessage, 0, ',', '.'),
            '{{BUKTI_DP_URL}}' => $proofUrl,
            '{{ALAMAT_JEMPUT}}' => $booking->alamat_jemput ?? '-',
            '{{ALAMAT_TUJUAN}}' => $booking->alamat_tujuan ?? '-',
            '{{DRIVER_INFO}}' => $driverInfo,
        ];

        $message = strtr($tpl, $replacements);

        // Jika variabel {{DRIVER_INFO}} tidak ada di template, tambahkan manual di akhir
        if (!str_contains($tpl, '{{DRIVER_INFO}}')) {
            $message .= $driverInfo;
        }

        if ($luarKotaInfo !== '' && !str_contains($tpl, '{{ALAMAT_TUJUAN}}')) {
            $message .= $luarKotaInfo;
        }

        if (
            !str_contains($tpl, '{{TGL_RANGE}}')
            && !str_contains($tpl, '{{TGL_MULAI}}')
            && !str_contains($tpl, '{{TGL_SELESAI}}')
        ) {
            $message .= "\n- *Tanggal*: " . $tglRangeStr;
        }

        if (
            !str_contains($tpl, '{{DURASI}}')
            && !str_contains($tpl, '{{DURASI_HARI}}')
            && !str_contains($tpl, '{{DURASI_JAM}}')
            && !str_contains($tpl, '{{DURASI_TRIP}}')
        ) {
            $message .= "\n- *Lama Sewa*: " . $durasiStr;
        }

        if (!str_contains($tpl, '{{DP}}')) {
            $message .= "\n- *DP*: " . 'Rp ' . number_format($dp, 0, ',', '.');
        }
        if (!str_contains($tpl, '{{SISA}}')) {
            $message .= "\n- *Sisa Tagihan*: " . 'Rp ' . number_format($sisa, 0, ',', '.');
        }
        if (!str_contains($tpl, '{{TOTAL_FULL}}')) {
            $message .= "\n- *Total Harga*: " . 'Rp ' . number_format($totalFull, 0, ',', '.');
        }

        if ($isDpConfirmation && !str_contains($tpl, '{{BUKTI_DP_URL}}')) {
            $message .= "\n🧾 Bukti bayar: " . $proofUrl;
        }

        return $message;
    }
}
