<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'nama',
        'kota',
        'kendaraan_disewa',
        'ulasan_id',
        'ulasan_en',
        'rating',
        'foto_avatar',
        'status',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
