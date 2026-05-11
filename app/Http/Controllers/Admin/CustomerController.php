<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer;
use Inertia\Inertia;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->withCount('bookings')->get();
        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers
        ]);
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'data_pelanggan_' . date('Y-m-d') . '.xlsx');
    }

    public function show($id)
    {
        $customer = Customer::with('bookings.vehicle')->findOrFail($id);
        return Inertia::render('Admin/Customers/Show', [
            'customer' => $customer
        ]);
    }

    public function blacklist(Request $request, $nik)
    {
        $customer = Customer::where('nik', $nik)->firstOrFail();
        $customer->update([
            'status' => 'blacklist',
            'blacklist_reason' => $request->reason
        ]);
        return back();
    }

    public function unblacklist($nik)
    {
        $customer = Customer::where('nik', $nik)->firstOrFail();
        $customer->update([
            'status' => 'aktif',
            'blacklist_reason' => null
        ]);
        return back();
    }
}
