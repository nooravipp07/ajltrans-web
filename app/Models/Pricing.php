<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'pricing';

    protected $fillable = [
        'vehicle_id',
        'kota',
        'paket_tipe',
        'unit',
        'harga_dasar',
        'harga_promo',
        'berlaku_mulai',
        'berlaku_sampai',
        'is_active',
    ];

    protected $casts = [
        'berlaku_mulai' => 'date',
        'berlaku_sampai' => 'date',
        'is_active' => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
