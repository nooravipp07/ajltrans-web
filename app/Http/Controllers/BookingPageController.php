<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ContentCms;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class BookingPageController extends Controller
{
    public function sewa(Request $request)
    {
        $serviceTypes = $this->serviceTypesFor('sewa_mobil');
        $requestedType = (string) $request->get('type', '');
        $allowed = array_map(fn ($t) => (string) ($t['slug'] ?? ''), $serviceTypes);
        $defaultServiceType = in_array($requestedType, $allowed, true)
            ? $requestedType
            : ($serviceTypes[0]['slug'] ?? 'lepas_kunci');
        $vehicles = Vehicle::with('pricings')->where('is_active', true)
            ->whereJsonContains('kategori', 'sewa_mobil')
            ->get();
        $selectedVehicleId = $request->get('vehicle_id');
        $selectedVehicle = $selectedVehicleId ? Vehicle::with('pricings')->find($selectedVehicleId) : null;
        return view('booking.sewa-mobil', compact('vehicles', 'selectedVehicleId', 'selectedVehicle', 'serviceTypes', 'defaultServiceType'));
    }

    /**
     * Alias for sewa() to prevent errors from old routes
     */
    public function sewaMobil(Request $request)
    {
        return $this->sewa($request);
    }

    public function tour(Request $request)
    {
        $serviceTypes = $this->serviceTypesFor('city_tour');
        $requestedType = (string) $request->get('type', '');
        $allowed = array_map(fn ($t) => (string) ($t['slug'] ?? ''), $serviceTypes);
        $defaultServiceType = in_array($requestedType, $allowed, true)
            ? $requestedType
            : ($serviceTypes[0]['slug'] ?? 'city_tour_allin');
        $vehicles = Vehicle::with('pricings')->where('is_active', true)
            ->whereJsonContains('kategori', 'city_tour')
            ->get();
        $selectedVehicleId = $request->get('vehicle_id');
        $selectedVehicle = $selectedVehicleId ? Vehicle::with('pricings')->find($selectedVehicleId) : null;
        return view('booking.city-tour', compact('vehicles', 'selectedVehicleId', 'selectedVehicle', 'serviceTypes', 'defaultServiceType'));
    }

    public function travel(Request $request)
    {
        $serviceTypes = $this->serviceTypesFor('travel');
        $requestedType = (string) $request->get('type', '');
        $allowed = array_map(fn ($t) => (string) ($t['slug'] ?? ''), $serviceTypes);
        $defaultServiceType = in_array($requestedType, $allowed, true)
            ? $requestedType
            : ($serviceTypes[0]['slug'] ?? 'travel_bandara');
        $vehicles = Vehicle::with('pricings')->where('is_active', true)
            ->whereJsonContains('kategori', 'travel')
            ->get();
        $selectedVehicleId = $request->get('vehicle_id');
        $selectedVehicle = $selectedVehicleId ? Vehicle::with('pricings')->find($selectedVehicleId) : null;
        return view('booking.travel', compact('vehicles', 'selectedVehicleId', 'selectedVehicle', 'serviceTypes', 'defaultServiceType'));
    }

    public function qris($code)
    {
        $booking = Booking::where('booking_code', $code)->with(['customer', 'vehicle'])->firstOrFail();
        
        // Cek apakah sudah bayar DP
        if ($booking->dp_status === 'paid' || $booking->dp_status === 'confirmed') {
            return redirect()->route('booking.success', $code);
        }
        
        // Ambil QRIS image dari CMS
        $qrisImage = \App\Models\ContentCms::where('section', 'payment')
            ->where('key', 'qris_image')
            ->first();
            
        return view('booking.qris', compact('booking', 'qrisImage'));
    }

    public function success($code)
    {
        $booking = Booking::where('booking_code', $code)->with(['customer', 'vehicle'])->firstOrFail();
        return view('booking.success', compact('booking'));
    }

    public function track($code)
    {
        $booking = Booking::where('booking_code', $code)->with(['customer', 'vehicle'])->firstOrFail();
        return view('booking.track', compact('booking'));
    }

    public function testimonial($code)
    {
        $booking = Booking::where('booking_code', $code)->with(['customer', 'vehicle'])->firstOrFail();
        return view('booking.testimonial', compact('booking'));
    }

    private function serviceTypesFor(string $kategori): array
    {
        $row = ContentCms::where('section', 'pricing')->where('key', 'service_types')->first();
        $decoded = $row?->value_id ? json_decode($row->value_id, true) : null;
        $decoded = is_array($decoded) ? $decoded : [];

        if (empty($decoded)) {
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

            $decoded = $defaults;
        }

        $filtered = array_values(array_filter($decoded, function ($t) use ($kategori) {
            $cats = (array) ($t['categories'] ?? []);
            if (empty($cats)) {
                return true;
            }
            return in_array($kategori, $cats, true);
        }));

        return array_values(array_map(function ($t) {
            return [
                'slug' => (string) ($t['slug'] ?? ''),
                'label' => (string) ($t['label'] ?? ''),
            ];
        }, $filtered));
    }
}
