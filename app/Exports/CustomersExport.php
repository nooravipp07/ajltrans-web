<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::withCount('bookings')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIK',
            'Nama Lengkap',
            'No. WhatsApp',
            'Alamat',
            'Status',
            'Total Pesanan',
            'Tanggal Terdaftar',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            "'" . $customer->nik, // Prefix with ' to prevent Excel from scientific notation
            $customer->nama,
            $customer->no_wa,
            $customer->alamat,
            ucfirst($customer->status),
            $customer->bookings_count,
            $customer->created_at->format('d/m/Y H:i'),
        ];
    }
}
