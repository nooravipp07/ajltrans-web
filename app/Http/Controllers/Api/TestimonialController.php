<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('sort_order')
            ->get();

        return response()->json($testimonials);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kendaraan_disewa' => 'required|string|max:255',
            'ulasan_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $testimonial = Testimonial::create([
            'nama' => $validated['nama'],
            'kota' => $validated['kota'],
            'kendaraan_disewa' => $validated['kendaraan_disewa'],
            'ulasan_id' => $validated['ulasan_id'],
            'ulasan_en' => $validated['ulasan_id'], // Default to same as ID
            'rating' => $validated['rating'],
            'status' => 'pending', // User input starts as pending
            'is_active' => false,  // Hidden until approved
            'is_featured' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Testimoni Anda berhasil dikirim dan menunggu moderasi admin.',
            'data' => $testimonial
        ]);
    }
}
