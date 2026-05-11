<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Gallery/Index', [
            'galleries' => Gallery::orderBy('sort_order')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_id' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'type' => 'required|in:photo,video',
            'url' => 'nullable|string', // will be overwritten if file
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('gallery', 'public');
            $validated['url'] = '/storage/' . $path;
        }

        Gallery::create($validated);

        return back()->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title_id' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'type' => 'required|in:photo,video',
            'url' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $gallery->update($validated);

        return back()->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'Galeri berhasil dihapus.');
    }
}
