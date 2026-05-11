<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'customer_nik',
        'vehicle_id',
        'kategori',
        'service_type',
        'kota',
        'tanggal_mulai',
        'durasi',
        'durasi_hari',
        'alamat_jemput',
        'alamat_tujuan',
        'harga_per_unit',
        'total_harga',
        'dp_amount',
        'dp_status',
        'dp_paid_at',
        'dp_qris_image',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'dp_paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_nik', 'nik');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mediaDocs()
    {
        return $this->hasMany(MediaDoc::class);
    }

    // Generate kode booking unik: AJL-2026-0001
    public static function generateCode(): string
    {
        $year  = now()->year;
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('AJL-%d-%04d', $year, $count);
    }
}
