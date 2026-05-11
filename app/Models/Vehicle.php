<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    private const JAKARTA_MARKUP = 200000;

    protected $fillable = [
        'nama',
        'tipe',
        'vehicle_size',
        'kategori',
        'harga_bdg',
        'harga_jkt',
        'harga_lepas_kunci_bdg',
        'harga_lepas_kunci_jkt',
        'harga_city_tour_allin_bdg',
        'harga_city_tour_allin_jkt',
        'harga_luar_kota_allin_bdg',
        'harga_luar_kota_allin_jkt',
        'harga_travel_bandara_bdg',
        'harga_travel_bandara_jkt',
        'foto_urls',
        'status',
        'badge',
        'tier',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'kategori' => 'array',
        'foto_urls' => 'array',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function pricings()
    {
        return $this->hasMany(Pricing::class);
    }

    public function getHargaForKota(string $kota): int
    {
        return $kota === 'jakarta' ? $this->harga_jkt : $this->harga_bdg;
    }

    public function getStartingPriceAttribute(): int
    {
        $min = $this->pricings()
            ->where('kota', 'bandung')
            ->where('harga_dasar', '>', 0)
            ->min('harga_dasar');
            
        return $min ?? 0;
    }

    public function getStartingPriceJktAttribute(): int
    {
        $minBdg = $this->pricings()
            ->where('kota', 'bandung')
            ->where('harga_dasar', '>', 0)
            ->min('harga_dasar');

        if ($minBdg !== null) {
            return (int) $minBdg + self::JAKARTA_MARKUP;
        }

        $minJkt = $this->pricings()
            ->where('kota', 'jakarta')
            ->where('harga_dasar', '>', 0)
            ->min('harga_dasar');

        return (int) ($minJkt ?? 0);
    }

    public function getFotoPrimaryAttribute(): ?string
    {
        $photos = $this->foto_urls ?? [];
        return count($photos) > 0 ? $photos[0] : null;
    }
}
