<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('pricings')->orderBy('sort_order')->get();
        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => $vehicles
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Vehicles/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'vehicle_size' => 'required|in:kecil,besar',
            'kategori' => 'required|array',
            'tier' => 'required|in:ekonomis,mid_range,premium',
            'status' => 'required|in:tersedia,tidak_tersedia,perawatan',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto_urls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('vehicles', 'public');
                $foto_urls[] = '/storage/' . $path;
            }
        }
        
        $validated['foto_urls'] = $foto_urls;
        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Armada berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        return Inertia::render('Admin/Vehicles/Form', [
            'vehicle' => $vehicle
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'vehicle_size' => 'required|in:kecil,besar',
            'kategori' => 'required|array',
            'tier' => 'required|in:ekonomis,mid_range,premium',
            'status' => 'required|in:tersedia,tidak_tersedia,perawatan',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'existing_photos' => 'nullable|array',
        ]);

        $foto_urls = $request->input('existing_photos', []);
        
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('vehicles', 'public');
                $foto_urls[] = '/storage/' . $path;
            }
        }

        $validated['foto_urls'] = $foto_urls;
        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Armada berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Armada berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update(['is_active' => !$vehicle->is_active]);
        return back();
    }
}
