<?php

namespace App\Http\Controllers;

use App\Models\ContentCms;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $cms = ContentCms::all()->groupBy('section')->map(function ($items) {
            return $items->keyBy('key');
        });

        $serviceTypes = $this->ensureServiceTypes();

        $vehicles = \App\Models\Vehicle::with('pricings')->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $posts = \App\Models\Post::where('is_published', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $galleries = \App\Models\Gallery::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $testimonials = \App\Models\Testimonial::where('is_featured', true)
            ->where('status', 'approved')
            ->orderBy('sort_order')
            ->get();

        return view('landing', compact('cms', 'serviceTypes', 'vehicles', 'posts', 'galleries', 'testimonials'));
    }

    public function vehicleDetail($id)
    {
        $vehicle = \App\Models\Vehicle::with('pricings')->findOrFail($id);
        return view('landing.vehicle-detail', compact('vehicle'));
    }

    public function newsDetail($slug)
    {
        $post = \App\Models\Post::where('slug', $slug)->firstOrFail();
        return view('landing.news-detail', compact('post'));
    }

    public function fleetAll(Request $request)
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search');
        
        $query = \App\Models\Vehicle::with('pricings')->where('is_active', true);
        
        if ($category !== 'all') {
            $query->whereJsonContains('kategori', $category);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->orderBy('sort_order')->paginate(12);
        return view('landing.fleet-all', compact('vehicles', 'category', 'search'));
    }

    public function galleryAll()
    {
        $galleries = \App\Models\Gallery::where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(16);
            
        return view('landing.gallery-all', compact('galleries'));
    }

    public function newsAll()
    {
        $posts = \App\Models\Post::where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
            
        return view('landing.news-all', compact('posts'));
    }

     public function setLanguage(Request $request)
     {
         $lang = $request->get('lang');
         if (in_array($lang, ['en', 'id'])) {
             session(['locale' => $lang]);
         }
         return back();
     }

    private function ensureServiceTypes(): array
    {
        $row = ContentCms::where('section', 'pricing')->where('key', 'service_types')->first();

        if (!$row || !$row->value_id) {
            $defaults = [
                ['slug' => 'lepas_kunci', 'label' => 'Lepas Kunci', 'categories' => ['sewa_mobil']],
                ['slug' => 'city_tour_allin', 'label' => 'City Tour All-In', 'categories' => ['sewa_mobil', 'city_tour']],
                ['slug' => 'luar_kota_allin', 'label' => 'Luar Kota All-In', 'categories' => ['sewa_mobil']],
                ['slug' => 'travel_bandara', 'label' => 'Travel Bandara', 'categories' => ['travel']],
            ];

            ContentCms::updateOrCreate(
                ['section' => 'pricing', 'key' => 'service_types'],
                [
                    'value_id' => json_encode($defaults, JSON_UNESCAPED_UNICODE),
                    'value_en' => json_encode($defaults, JSON_UNESCAPED_UNICODE),
                    'type' => 'json',
                ]
            );

            return $defaults;
        }

        $decoded = json_decode($row->value_id, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_map(function ($t) {
            return [
                'slug' => (string) ($t['slug'] ?? ''),
                'label' => (string) ($t['label'] ?? ''),
                'categories' => array_values(array_unique(array_filter((array) ($t['categories'] ?? [])))),
            ];
        }, $decoded));
    }
 }
