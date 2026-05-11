<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kendaraan_disewa' => 'required|string|max:255',
            'ulasan_id' => 'required|string',
            'ulasan_en' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
            'sort_order' => 'integer',
        ]);

        Testimonial::create($validated);

        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kendaraan_disewa' => 'required|string|max:255',
            'ulasan_id' => 'required|string',
            'ulasan_en' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
            'sort_order' => 'integer',
        ]);

        $testimonial->update($validated);

        return back()->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
