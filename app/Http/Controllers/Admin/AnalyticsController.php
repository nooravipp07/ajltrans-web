<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // For Inertia dashboard index
        return inertia('Admin/Analytics/Index');
    }

    public function data(Request $request): JsonResponse
    {
        $year = $request->get('year', now()->year);

        return response()->json([
            'marketing' => $this->marketingData(),
            'keuangan' => $this->keuanganData((int) $year),
            'crm' => $this->crmData(),
        ]);
    }

    // Panel Marketing: Booking per kota, konversi form ke WA 
    public function marketingData(): array
    {
        return [
            'booking_per_kota' => Booking::groupBy('kota')
                ->selectRaw('kota, COUNT(*) as total')
                ->get(),
            'booking_per_kategori' => Booking::groupBy('kategori')
                ->selectRaw('kategori, COUNT(*) as total')
                ->get(),
            'booking_status_dist' => Booking::groupBy('status')
                ->selectRaw('status, COUNT(*) as total')
                ->get(),
            'kendaraan_terpopuler' => Booking::groupBy('vehicle_id')
                ->selectRaw('vehicle_id, COUNT(*) as total')
                ->with('vehicle:id,nama')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ];
    }

    // Panel Keuangan: Revenue per kota & kategori 
    public function keuanganData(int $year): array
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthExpression = $isSqlite ? "strftime('%m', created_at)" : "MONTH(created_at)";

        $rawMonthly = Booking::where('status', 'selesai')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw($monthExpression))
            ->selectRaw("$monthExpression as bulan, SUM(total_harga) as revenue")
            ->get();

        // Fill missing months with zero
        $monthlyTrend = collect(range(1, 12))->map(function ($month) use ($rawMonthly, $isSqlite) {
            // If MySQL, $month is compared to integer 1-12. If SQLite, compared to "01"-"12"
            $searchBulan = $isSqlite ? str_pad($month, 2, '0', STR_PAD_LEFT) : (int)$month;
            $data = $rawMonthly->first(function($item) use ($searchBulan) {
                return (int)$item->bulan === (int)$searchBulan;
            });
            
            return [
                'bulan' => date('M', mktime(0, 0, 0, $month, 1)),
                'revenue' => $data ? (int) $data->revenue : 0
            ];
        });

        return [
            'revenue_per_kota' => Booking::where('status', 'selesai')
                ->whereYear('created_at', $year)
                ->groupBy('kota')
                ->selectRaw('kota, SUM(total_harga) as revenue')
                ->get(),
            'tren_bulanan' => $monthlyTrend,
        ];
    }

    // Panel CRM: Customer baru vs repeat 
    public function crmData(): array
    {
        return [
            'customer_baru' => Customer::whereDoesntHave('bookings', function ($q) {
                $q->where('created_at', '<', now()->subMonth());
            })->count(),
            'repeat_order' => Customer::has('bookings', '>=', 2)->count(),
            'top_customers' => Customer::withCount('bookings')
                ->orderByDesc('bookings_count')
                ->limit(10)
                ->get(),
        ];
    }
}
