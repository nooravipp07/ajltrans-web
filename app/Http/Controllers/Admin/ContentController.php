<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentCms;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Content/Index', [
            'contents' => ContentCms::all()
        ]);
    }

    public function update(Request $request, $section)
    {
        $items = $request->input('items', []);
        
        foreach ($items as $index => $item) {
            if ($item['section'] === $section) {
                $updateData = [
                    'value_id' => $item['value_id'],
                    'value_en' => $item['value_en'],
                ];

                // Handle file upload if it's a file
                if ($request->hasFile("items.$index.value_id_file")) {
                    $path = $request->file("items.$index.value_id_file")->store('cms', 'public');
                    $updateData['value_id'] = '/storage/' . $path;
                    $updateData['value_en'] = '/storage/' . $path; // Usually logos are same for both
                }

                ContentCms::where('id', $item['id'])->update($updateData);
            }
        }

        return back()->with('success', 'Konten berhasil diperbarui.');
    }

    public function uploadQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('qris_image')->store('qris', 'public');

        // Update or create QRIS content
        ContentCms::updateOrCreate(
            ['section' => 'payment', 'key' => 'qris_image'],
            [
                'value_id' => $path,
                'value_en' => $path,
                'type' => 'image'
            ]
        );

        return back()->with('success', 'QRIS Image berhasil diupload.');
    }
}
