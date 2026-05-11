<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Booking::with(['customer', 'vehicle'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Kode Booking',
            'Customer',
            'WhatsApp',
            'Kendaraan',
            'Kategori',
            'Kota',
            'Tgl Mulai',
            'Tgl Selesai',
            'Total Harga',
            'Status',
            'Tgl Pesanan',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->booking_code,
            $booking->customer->nama,
            $booking->customer->no_wa,
            $booking->vehicle->nama,
            ucwords(str_replace('_', ' ', $booking->kategori)),
            ucfirst($booking->kota),
            $booking->tanggal_mulai,
            $booking->tanggal_selesai,
            $booking->total_harga,
            ucwords(str_replace('_', ' ', $booking->status)),
            $booking->created_at->format('d/m/Y H:i'),
        ];
    }
}
