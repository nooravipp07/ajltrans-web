<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_bookings' => Booking::count(),
                'pending_bookings' => Booking::where('status', 'menunggu_konfirmasi')->count(),
                'total_customers' => Customer::count(),
                'total_vehicles' => Vehicle::count(),
            ],
            'recent_bookings' => Booking::with(['customer', 'vehicle'])->latest()->limit(5)->get()
        ]);
    }
}
