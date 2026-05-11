<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function publicIndex(): JsonResponse
    {
        $vehicles = Vehicle::with('pricings')->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($vehicles);
    }

    public function publicShow($id): JsonResponse
    {
        $vehicle = Vehicle::with('pricings')->where('is_active', true)->findOrFail($id);
        return response()->json($vehicle);
    }
}
