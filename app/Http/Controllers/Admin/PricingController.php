<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentCms;
use App\Models\Pricing;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
    private const JAKARTA_MARKUP = 200000;
    private const UNITS = ['per_12_jam', 'per_18_jam', 'per_hari'];

    public function index()
    {
        $serviceTypes = $this->ensureServiceTypes();
        $vehicles = Vehicle::with('pricings')->orderBy('nama')->get();
        
        return Inertia::render('Admin/Pricing/Index', [
            'vehicles' => $vehicles,
            'service_types' => $serviceTypes,
        ]);
    }

    public function store(Request $request)
    {
        $serviceTypeSlugs = array_map(fn ($t) => (string) ($t['slug'] ?? ''), $this->ensureServiceTypes());

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'prices' => 'required|array',
            'prices.*.kota' => 'required|in:bandung',
            'prices.*.paket_tipe' => 'required|string|max:64',
            'prices.*.unit' => 'required|in:per_12_jam,per_18_jam,per_hari',
            'prices.*.harga_dasar' => 'required|numeric|min:0',
            'prices.*.harga_promo' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->prices as $priceData) {
                if (!in_array($priceData['paket_tipe'], $serviceTypeSlugs, true)) {
                    throw new \RuntimeException('Jenis sewa tidak valid: ' . $priceData['paket_tipe']);
                }

                Pricing::updateOrCreate(
                    [
                        'vehicle_id' => $request->vehicle_id,
                        'kota' => 'bandung',
                        'paket_tipe' => $priceData['paket_tipe'],
                        'unit' => $priceData['unit'],
                    ],
                    [
                        'harga_dasar' => $priceData['harga_dasar'],
                        'harga_promo' => $priceData['harga_promo'] ?? null,
                    ]
                );

                Pricing::where('vehicle_id', $request->vehicle_id)
                    ->where('kota', 'jakarta')
                    ->where('paket_tipe', $priceData['paket_tipe'])
                    ->delete();
            }
            
            DB::commit();
            return back()->with('success', 'Harga armada berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateServiceTypes(Request $request)
    {
        $validated = $request->validate([
            'service_types' => 'required|array|min:1',
            'service_types.*.slug' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9_]+$/'],
            'service_types.*.label' => 'required|string|max:80',
            'service_types.*.categories' => 'nullable|array',
            'service_types.*.categories.*' => 'in:sewa_mobil,city_tour,travel',
        ]);

        $serviceTypes = array_values(array_map(function ($t) {
            return [
                'slug' => (string) $t['slug'],
                'label' => (string) $t['label'],
                'categories' => array_values(array_unique(array_filter((array) ($t['categories'] ?? [])))),
            ];
        }, $validated['service_types']));

        $slugs = array_map(fn ($t) => $t['slug'], $serviceTypes);
        if (count($slugs) !== count(array_unique($slugs))) {
            return back()->with('error', 'Slug jenis sewa tidak boleh duplikat.');
        }

        ContentCms::updateOrCreate(
            ['section' => 'pricing', 'key' => 'service_types'],
            [
                'value_id' => json_encode($serviceTypes, JSON_UNESCAPED_UNICODE),
                'value_en' => json_encode($serviceTypes, JSON_UNESCAPED_UNICODE),
                'type' => 'json',
            ]
        );

        return back()->with('success', 'Jenis sewa berhasil diperbarui.');
    }

    public function downloadTemplate()
    {
        $serviceTypes = $this->ensureServiceTypes();

        $headers = [
            'vehicle_id',
            'nama',
            'tipe',
            'vehicle_size',
            'tier',
            'status',
            'badge',
            'is_active',
            'sort_order',
            'kategori',
        ];

        foreach ($serviceTypes as $t) {
            $slug = (string) ($t['slug'] ?? '');
            $headers[] = "harga_{$slug}_12j";
            $headers[] = "promo_{$slug}_12j";
            $headers[] = "harga_{$slug}_18j";
            $headers[] = "promo_{$slug}_18j";
            $headers[] = "harga_{$slug}_hari";
            $headers[] = "promo_{$slug}_hari";
        }

        $vehicles = Vehicle::with('pricings')->orderBy('nama')->get();

        $rows = [];
        $rows[] = $headers;

        foreach ($vehicles as $vehicle) {
            $row = [
                $vehicle->id,
                $vehicle->nama,
                $vehicle->tipe,
                $vehicle->vehicle_size ?? 'kecil',
                $vehicle->tier,
                $vehicle->status,
                $vehicle->badge ?? 'none',
                $vehicle->is_active ? 1 : 0,
                $vehicle->sort_order ?? 0,
                implode('|', $vehicle->kategori ?? []),
            ];

            foreach ($serviceTypes as $t) {
                $slug = (string) ($t['slug'] ?? '');
                foreach (self::UNITS as $unit) {
                    $pricingBdg = $vehicle->pricings
                        ->first(fn ($p) => $p->kota === 'bandung' && $p->paket_tipe === $slug && (($p->unit ?? 'per_hari') === $unit));
                    $pricingJkt = $vehicle->pricings
                        ->first(fn ($p) => $p->kota === 'jakarta' && $p->paket_tipe === $slug && (($p->unit ?? 'per_hari') === $unit));

                    $base = $pricingBdg?->harga_dasar;
                    if ($base === null && $pricingJkt) {
                        $base = max(((int) $pricingJkt->harga_dasar) - self::JAKARTA_MARKUP, 0);
                    }

                    $promo = $pricingBdg?->harga_promo;
                    if ($promo === null && $pricingJkt) {
                        $promo = $pricingJkt->harga_promo;
                        if ($promo !== null) {
                            $promo = max(((int) $promo) - self::JAKARTA_MARKUP, 0);
                        }
                    }

                    $row[] = $base ?? '';
                    $row[] = $promo ?? '';
                }
            }

            $rows[] = $row;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($rows, null, 'A1', true);

        $sheet->freezePane('A2');
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $callback = function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        };

        return Response::streamDownload($callback, 'ajl_pricing_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $serviceTypes = $this->ensureServiceTypes();

        $filePath = $request->file('file')->getRealPath();
        $ext = strtolower((string) $request->file('file')->getClientOriginalExtension());

        $rows = [];
        if (in_array($ext, ['xlsx', 'xls'], true)) {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);
        } else {
            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                return back()->with('error', 'File tidak bisa dibaca.');
            }

            while (($r = fgetcsv($handle)) !== false) {
                $rows[] = $r;
            }
            fclose($handle);
        }

        if (count($rows) < 2) {
            return back()->with('error', 'Template kosong atau tidak valid.');
        }

        $headerRow = array_map(fn ($h) => trim((string) $h), (array) $rows[0]);
        $headerMap = [];
        foreach ($headerRow as $idx => $h) {
            if ($h !== '') {
                $headerMap[$h] = $idx;
            }
        }

        $get = function (array $row, string $key) use ($headerMap) {
            if (!array_key_exists($key, $headerMap)) {
                return null;
            }
            $val = $row[$headerMap[$key]] ?? null;
            $val = is_string($val) ? trim($val) : $val;
            return $val === '' ? null : $val;
        };

        $updatedVehicles = 0;
        $createdVehicles = 0;
        $updatedPrices = 0;
        $dedupRemovedVehicles = 0;

        DB::beginTransaction();
        try {
            foreach (array_slice($rows, 1) as $row) {
                $row = (array) $row;
                $vehicleId = $get($row, 'vehicle_id');
                $nama = $get($row, 'nama');

                if ($vehicleId === null && $nama === null) {
                    continue;
                }

                $vehicle = null;
                if ($vehicleId !== null && is_numeric($vehicleId)) {
                    $vehicle = Vehicle::find((int) $vehicleId);
                }

                if (!$vehicle && $nama !== null) {
                    $vehicle = Vehicle::where('nama', trim((string) $nama))->first();
                }

                $payload = [];
                foreach (['nama', 'tipe', 'vehicle_size', 'tier', 'status', 'badge'] as $field) {
                    $val = $get($row, $field);
                    if ($val !== null) {
                        $payload[$field] = $val;
                    }
                }

                $isActive = $get($row, 'is_active');
                if ($isActive !== null) {
                    $payload['is_active'] = in_array(strtolower((string) $isActive), ['1', 'true', 'ya', 'yes'], true);
                }

                $sortOrder = $get($row, 'sort_order');
                if ($sortOrder !== null && is_numeric($sortOrder)) {
                    $payload['sort_order'] = (int) $sortOrder;
                }

                $kategori = $get($row, 'kategori');
                if ($kategori !== null) {
                    $parts = preg_split('/[|,]/', $kategori) ?: [];
                    $parts = array_values(array_filter(array_map(fn ($p) => trim($p), $parts)));
                    $payload['kategori'] = $parts;
                }

                if (!$vehicle) {
                    $vehicle = Vehicle::create(array_merge([
                        'nama' => $nama ?? 'Unnamed',
                        'tipe' => $payload['tipe'] ?? '-',
                        'vehicle_size' => $payload['vehicle_size'] ?? 'kecil',
                        'kategori' => $payload['kategori'] ?? [],
                        'tier' => $payload['tier'] ?? 'ekonomis',
                        'status' => $payload['status'] ?? 'tersedia',
                        'badge' => $payload['badge'] ?? 'none',
                        'is_active' => $payload['is_active'] ?? true,
                        'sort_order' => $payload['sort_order'] ?? 0,
                        'foto_urls' => [],
                    ], Arr::except($payload, ['foto_urls'])));
                    $createdVehicles++;
                } else {
                    if (!empty($payload)) {
                        $vehicle->update($payload);
                        $updatedVehicles++;
                    }
                }

                if ($vehicle && $vehicle->nama) {
                    $result = $this->mergeDuplicateVehiclesByName($vehicle->nama);
                    $vehicle = $result['vehicle'];
                    $dedupRemovedVehicles += (int) $result['removed'];
                }

                foreach ($serviceTypes as $t) {
                    $slug = (string) ($t['slug'] ?? '');

                    $map = [
                        'per_12_jam' => ['harga' => "harga_{$slug}_12j", 'promo' => "promo_{$slug}_12j"],
                        'per_18_jam' => ['harga' => "harga_{$slug}_18j", 'promo' => "promo_{$slug}_18j"],
                        'per_hari' => ['harga' => "harga_{$slug}_hari", 'promo' => "promo_{$slug}_hari"],
                    ];

                    foreach ($map as $unit => $keys) {
                        $harga = $get($row, $keys['harga']);
                        $promo = $get($row, $keys['promo']);

                        if ($unit === 'per_hari' && $harga === null) {
                            $harga = $get($row, "harga_{$slug}");
                            if ($harga === null) {
                                $harga = $get($row, "harga_{$slug}_bandung");
                            }
                        }
                        if ($unit === 'per_hari' && $promo === null) {
                            $promo = $get($row, "promo_{$slug}");
                            if ($promo === null) {
                                $promo = $get($row, "promo_{$slug}_bandung");
                            }
                        }

                        if ($harga === null && $promo === null) {
                            continue;
                        }

                        $hargaVal = $harga !== null && is_numeric($harga) ? (int) $harga : 0;
                        $promoVal = $promo !== null && is_numeric($promo) ? (int) $promo : null;

                        Pricing::updateOrCreate(
                            [
                                'vehicle_id' => $vehicle->id,
                                'kota' => 'bandung',
                                'paket_tipe' => $slug,
                                'unit' => $unit,
                            ],
                            [
                                'harga_dasar' => $hargaVal,
                                'harga_promo' => $promoVal,
                            ]
                        );
                        $updatedPrices++;
                    }

                    Pricing::where('vehicle_id', $vehicle->id)
                        ->where('kota', 'jakarta')
                        ->where('paket_tipe', $slug)
                        ->delete();
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('success', "Import selesai. Duplicate dihapus: {$dedupRemovedVehicles}, Vehicle baru: {$createdVehicles}, Vehicle update: {$updatedVehicles}, Harga update: {$updatedPrices}");
    }

    /**
     * Update is handled by store (updateOrCreate)
     */
    public function update(Request $request, Pricing $pricing)
    {
        return $this->store($request);
    }

    public function destroy($vehicleId)
    {
        Pricing::where('vehicle_id', $vehicleId)->delete();
        return back()->with('success', 'Semua harga armada berhasil dihapus.');
    }

    private function ensureServiceTypes(): array
    {
        $row = ContentCms::where('section', 'pricing')->where('key', 'service_types')->first();

        if (!$row || !$row->value_id) {
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

            return $defaults;
        }

        $decoded = json_decode($row->value_id, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_map(function ($t) {
            return [
                'slug' => (string) ($t['slug'] ?? ''),
                'label' => (string) ($t['label'] ?? ''),
                'categories' => array_values(array_unique(array_filter((array) ($t['categories'] ?? [])))),
            ];
        }, $decoded));
    }

    private function mergeDuplicateVehiclesByName(string $nama): array
    {
        $namaNorm = trim($nama);
        if ($namaNorm === '') {
            return ['vehicle' => null, 'removed' => 0];
        }

        $matches = Vehicle::with(['pricings'])->where('nama', $namaNorm)->orderBy('id')->get();
        if ($matches->count() <= 1) {
            return ['vehicle' => $matches->first(), 'removed' => 0];
        }

        $keep = $matches->first();
        $removed = 0;

        foreach ($matches->slice(1) as $dup) {
            $dup->bookings()->update(['vehicle_id' => $keep->id]);

            foreach ($dup->pricings as $p) {
                Pricing::updateOrCreate(
                    [
                        'vehicle_id' => $keep->id,
                        'kota' => $p->kota,
                        'paket_tipe' => $p->paket_tipe,
                    ],
                    [
                        'harga_dasar' => (int) $p->harga_dasar,
                        'harga_promo' => $p->harga_promo,
                    ]
                );
            }

            $dup->delete();
            $removed++;
        }

        return ['vehicle' => $keep->fresh(['pricings']), 'removed' => $removed];
    }
}
