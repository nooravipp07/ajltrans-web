<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function checkNik(Request $request): JsonResponse
    {
        $nik = $request->validate(['nik' => 'required|digits:16'])['nik'];

        $customer = Customer::where('nik', $nik)->first();

        if (!$customer) {
            return response()->json(['status' => 'new', 'data' => null]);
        }

        if ($customer->isBlacklisted()) {
            return response()->json([
                'status'  => 'blacklisted',
                'message' => 'NIK ini tidak dapat melakukan pemesanan.',
            ], 403);
        }

        return response()->json([
            'status' => 'exists',
            'data'   => [
                'nama'           => $customer->nama,
                'alamat'         => $customer->alamat,
                'no_wa'          => $customer->no_wa,
                'foto_identitas' => $customer->foto_identitas
                    ? Storage::url($customer->foto_identitas) : null,
            ],
        ]);
    }
}
