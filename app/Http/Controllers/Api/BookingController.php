<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContentCms;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class BookingController extends Controller
{
    private const JAKARTA_MARKUP = 200000;
    private const UNITS = ['per_12_jam', 'per_18_jam', 'per_hari'];

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_wa' => 'required|string',
            'vehicle_id' => 'required|exists:vehicles,id',
            'kategori' => 'required|in:sewa_mobil,city_tour,travel',
            'service_type' => 'required|string|max:64',
            'durasi_unit' => 'nullable|string|max:16',
            'kota' => 'required|in:bandung,jakarta',
            'tanggal_mulai' => 'required|date',
            'alamat_jemput' => 'nullable|string|max:255',
            'alamat_tujuan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Upsert Customer
            $customer = Customer::updateOrCreate(
                ['nik' => $request->nik],
                [
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'no_wa' => $request->no_wa,
                    'status' => 'aktif',
                ]
            );

            // Handle Photo Identity if new customer or re-upload
            if ($request->hasFile('foto_identitas')) {
                $path = $request->file('foto_identitas')->store('identitas', 'public');
                $customer->update(['foto_identitas' => $path]);
            }

            // 2. Hitung Harga 
            $vehicle = Vehicle::with('pricings')->findOrFail($request->vehicle_id);
            $serviceType = $request->service_type;
            $city = $request->kota;
            $kategori = $request->kategori;
            $isLuarKota = $kategori === 'sewa_mobil' && str_contains(strtolower($serviceType), 'luar_kota');
            $isDaily = $kategori === 'sewa_mobil' ? ($this->isDailyVehicle($vehicle) || $isLuarKota) : false;
            $durasiUnit = $isDaily ? 'per_hari' : ((string) ($request->durasi_unit ?: 'per_12_jam'));
            if (!in_array($durasiUnit, self::UNITS, true)) {
                $durasiUnit = 'per_12_jam';
            }
            if ($kategori !== 'sewa_mobil') {
                $durasiUnit = 'per_hari';
            }
            if ($isLuarKota) {
                $durasiUnit = 'per_hari';
            }

            if ($kategori === 'sewa_mobil') {
                if ($isDaily) {
                    $days = (int) $request->durasi_hari;
                    $minDays = $isLuarKota ? 2 : 1;
                    if ($days < $minDays) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Durasi sewa (hari) wajib diisi.',
                            'errors' => [
                                'durasi_hari' => ['Durasi sewa (hari) wajib diisi.'],
                            ],
                        ], 422);
                    }
                } else {
                    $hours = (int) $request->durasi;
                    $unitHours = $durasiUnit === 'per_18_jam' ? 18 : 12;
                    if ($hours < $unitHours || ($hours % $unitHours) !== 0) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Durasi sewa (jam) wajib kelipatan ' . $unitHours . ' jam.',
                            'errors' => [
                                'durasi' => ['Durasi sewa (jam) wajib kelipatan ' . $unitHours . ' jam.'],
                            ],
                        ], 422);
                    }
                }
            }

            if ($kategori === 'city_tour') {
                $days = (int) $request->durasi_hari;
                if ($days < 1) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Durasi tour (hari) wajib diisi.',
                        'errors' => [
                            'durasi_hari' => ['Durasi tour (hari) wajib diisi.'],
                        ],
                    ], 422);
                }
            }

            if ($kategori === 'travel') {
                $trip = (int) ($request->durasi_hari ?? 1);
                if ($trip < 1) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jumlah trip wajib diisi.',
                        'errors' => [
                            'durasi_hari' => ['Jumlah trip wajib diisi.'],
                        ],
                    ], 422);
                }
                if (!$request->alamat_jemput || !$request->alamat_tujuan) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Alamat jemput dan tujuan wajib diisi.',
                        'errors' => [
                            'alamat_jemput' => ['Alamat jemput wajib diisi.'],
                            'alamat_tujuan' => ['Alamat tujuan wajib diisi.'],
                        ],
                    ], 422);
                }
            }

            $serviceTypesRow = ContentCms::where('section', 'pricing')->where('key', 'service_types')->first();
            $serviceTypes = $serviceTypesRow?->value_id ? json_decode($serviceTypesRow->value_id, true) : null;
            $serviceTypes = is_array($serviceTypes) ? $serviceTypes : [];
            if (empty($serviceTypes)) {
                $defaults = [
                    ['slug' => 'lepas_kunci', 'label' => 'Lepas Kunci', 'categories' => ['sewa_mobil']],
                    ['slug' => 'city_tour_allin', 'label' => 'City Tour All-In', 'categories' => ['sewa_mobil', 'city_tour']],
                    ['slug' => 'luar_kota_allin', 'label' => 'Luar Kota All-In', 'categories' => ['sewa_mobil']],
                    ['slug' => 'travel_bandara', 'label' => 'Travel Bandara', 'categories' => ['travel']],
                ];

                ContentCms::updateOrCreate(
                    ['section' => 'pricing', 'key' => 'service_types'],
                    [
                        'value_id' => json_encode($defaults, JSON_UNESCAPED_UNICODE),
                        'value_en' => json_encode($defaults, JSON_UNESCAPED_UNICODE),
                        'type' => 'json',
                    ]
                );

                $serviceTypes = $defaults;
            }

            $serviceTypeMeta = collect($serviceTypes)->first(function ($t) use ($serviceType) {
                return (string) ($t['slug'] ?? '') === $serviceType;
            });

            $allowedCategories = (array) ($serviceTypeMeta['categories'] ?? []);
            if (!$serviceTypeMeta || (!empty($allowedCategories) && !in_array($kategori, $allowedCategories, true))) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jenis layanan tidak valid.',
                    'errors' => [
                        'service_type' => ['Jenis layanan tidak valid.'],
                    ],
                ], 422);
            }

            if ($isLuarKota && !$this->isDailyVehicle($vehicle)) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Layanan luar kota tidak tersedia untuk kendaraan kecil.',
                    'errors' => [
                        'vehicle_id' => ['Layanan luar kota tidak tersedia untuk kendaraan kecil.'],
                    ],
                ], 422);
            }

            $pricingBdg = $vehicle->pricings()
                ->where('kota', 'bandung')
                ->where('paket_tipe', $serviceType)
                ->where('unit', $durasiUnit)
                ->first();

            $pricingJkt = $vehicle->pricings()
                ->where('kota', 'jakarta')
                ->where('paket_tipe', $serviceType)
                ->where('unit', $durasiUnit)
                ->first();

            $baseBdg = null;
            if ($pricingBdg && (int) $pricingBdg->harga_dasar > 0) {
                $baseBdg = (int) $pricingBdg->harga_dasar;
            } elseif ($pricingJkt && (int) $pricingJkt->harga_dasar > 0) {
                $baseBdg = max(((int) $pricingJkt->harga_dasar) - self::JAKARTA_MARKUP, 0);
            }

            if (!$baseBdg || $baseBdg <= 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harga untuk layanan ini belum tersedia.',
                    'errors' => [
                        'service_type' => ['Harga untuk layanan ini belum tersedia.'],
                    ],
                ], 422);
            }

            $harga = $city === 'jakarta' ? ($baseBdg + self::JAKARTA_MARKUP) : $baseBdg;
            
            $total = 0;
            if ($kategori === 'sewa_mobil') {
                if ($isDaily) {
                    $minDays = $isLuarKota ? 2 : 1;
                    $total = $harga * max((int) $request->durasi_hari, $minDays);
                } else {
                    $unitHours = $durasiUnit === 'per_18_jam' ? 18 : 12;
                    $total = $harga * ceil(((int) $request->durasi) / $unitHours);
                }
            } else {
                $total = $harga * ($request->durasi_hari ?? 1);
            }

            // Hitung DP amount
            $dpAmount = 0;
            if ($request->kategori === 'sewa_mobil') {
                if ($this->isDailyVehicle($vehicle)) {
                    $dpAmount = (int) round($total * 0.5);
                } else {
                    $dpAmount = 200000;
                }
            } else {
                $dpAmount = 500000;
            }

            // 3. Create Booking
            $booking = Booking::create([
                'booking_code' => $this->generateBookingCode(),
                'customer_nik' => $customer->nik,
                'vehicle_id' => $vehicle->id,
                'kategori' => $request->kategori,
                'service_type' => $request->service_type,
                'kota' => $request->kota,
                'tanggal_mulai' => $request->tanggal_mulai,
                'durasi' => $isDaily ? null : $request->durasi,
                'durasi_hari' => $request->durasi_hari,
                'alamat_jemput' => $request->alamat_jemput,
                'alamat_tujuan' => $request->alamat_tujuan,
                'harga_per_unit' => $harga,
                'total_harga' => $total,
                'dp_amount' => $dpAmount,
                'dp_status' => 'pending',
                'status' => 'menunggu_konfirmasi',
                'catatan_admin' => $request->dengan_supir ? 'Gunakan Sopir' : null,
            ]);

            // 4. Handle Media Documentation
            if ($request->hasFile('media_docs')) {
                foreach ($request->file('media_docs') as $file) {
                    $path = $file->store('bookings', 'public');
                    $booking->mediaDocs()->create([
                        'tipe' => str_contains($file->getMimeType(), 'video') ? 'video' : 'foto',
                        'url' => $path
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil disimpan',
                'booking_code' => $booking->booking_code,
                'redirect_url' => route('booking.qris', $booking->booking_code)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmDp(Request $request, $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            if (!$booking->dp_qris_image) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bukti pembayaran wajib diupload sebelum konfirmasi.',
                ], 422);
            }

            if ($booking->dp_status !== 'confirmed') {
                $booking->update([
                    'dp_status' => 'paid',
                    'dp_paid_at' => $booking->dp_paid_at ?? now(),
                ]);
            }

            $booking->load(['customer', 'vehicle']);
            $dpCategory = match ($booking->kategori) {
                'sewa_mobil' => 'dp_confirmation_sewa_mobil',
                'city_tour' => 'dp_confirmation_city_tour',
                'travel' => 'dp_confirmation_travel',
                default => 'dp_confirmation',
            };
            $waLink = app(WhatsAppService::class)->generateLink($booking, $dpCategory);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran DP berhasil dikonfirmasi',
                'booking_code' => $booking->booking_code,
                'wa_link' => $waLink,
                'redirect_url' => route('booking.success', $booking->booking_code),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadDpProof(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'dp_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        try {
            $booking = Booking::findOrFail($id);

            $path = $validated['dp_proof']->store('dp_proofs', 'public');
            $booking->update(['dp_qris_image' => $path]);

            return response()->json([
                'status' => 'success',
                'message' => 'Bukti pembayaran berhasil diupload.',
                'dp_proof_path' => $path,
                'dp_proof_url' => URL::to('storage/' . ltrim($path, '/')),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal upload bukti pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function isDailyVehicle(Vehicle $vehicle): bool
    {
        if (($vehicle->vehicle_size ?? null) === 'besar') {
            return true;
        }

        $name = strtolower((string) ($vehicle->nama ?? ''));
        $tipe = strtolower((string) ($vehicle->tipe ?? ''));
        $haystack = $name . ' ' . $tipe;

        return str_contains($haystack, 'hiace')
            || str_contains($haystack, 'haice')
            || str_contains($haystack, 'elf')
            || str_contains($haystack, 'coaster')
            || str_contains($haystack, 'bus');
    }

    private function generateBookingCode()
    {
        $year = date('Y');
        $count = Booking::whereYear('created_at', $year)->count() + 1;
        return 'AJL-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
