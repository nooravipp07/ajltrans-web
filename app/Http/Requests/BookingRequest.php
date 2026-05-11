<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $base = [
            'nik'           => 'required|digits:16',
            'nama'          => 'required|string|max:255',
            'alamat'        => 'required|string|max:1000',
            'no_wa'         => 'required|string|max:20',
            'vehicle_id'    => 'required|exists:vehicles,id',
            'kota'          => 'required|in:bandung,jakarta',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'kategori'      => 'required|in:sewa_mobil,city_tour,travel',
            'service_type'  => 'required|in:lepas_kunci,city_tour_allin,luar_kota_allin,travel_bandara',
            'foto_identitas' => 'sometimes|image|mimes:jpg,jpeg,png|max:5120',
            'media_docs'    => 'sometimes|array|max:5',
            'media_docs.*'  => 'image|mimes:jpg,jpeg,png|max:5120',
        ];

        $kategori = $this->input('kategori');

        return match ($kategori) {
            'sewa_mobil' => array_merge($base, [
                'durasi' => 'required|integer|min:1|max:30', // per 12 jam 
            ]),
            'city_tour' => array_merge($base, [
                'durasi_hari' => 'required|integer|min:1|max:30',
            ]),
            'travel' => array_merge($base, [
                'alamat_jemput' => 'required|string|max:500',
                'alamat_tujuan' => 'required|string|max:500',
            ]),
            default => $base,
        };
    }
}
