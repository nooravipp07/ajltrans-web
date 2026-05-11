<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaDoc extends Model
{
    protected $fillable = [
        'booking_id',
        'tipe',
        'url',
        'keterangan',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
