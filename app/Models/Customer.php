<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'no_wa',
        'foto_identitas',
        'status',
        'blacklist_reason',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_nik', 'nik');
    }

    public function isBlacklisted(): bool
    {
        return $this->status === 'blacklist';
    }

    // Scope: cari by NIK exact
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }
}
