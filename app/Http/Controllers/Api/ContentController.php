<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentCms;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function index(): JsonResponse
    {
        $content = ContentCms::all()->groupBy('section')->map(function ($items) {
            return $items->keyBy('key')->map(function ($item) {
                return [
                    'id' => $item->value_id,
                    'en' => $item->value_en,
                    'type' => $item->type
                ];
            });
        });

        return response()->json($content);
    }
}
