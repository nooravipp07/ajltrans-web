<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\WaTemplateController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MediaDocController;
use App\Http\Controllers\Api\WhatsAppController;

// Semua route ini untuk landing page (tidak butuh auth) 
Route::prefix('v1')->middleware('throttle:60,1')->group(function () { 
 
    // ── Landing Page Content ───────────────────────── 
    Route::get('/content', [ContentController::class, 'index']); 
    Route::get('/vehicles', [VehicleController::class, 'publicIndex']); 
    Route::get('/vehicles/{id}', [VehicleController::class, 'publicShow']); 
    Route::get('/testimonials', [TestimonialController::class, 'index']); 
    Route::post('/testimonials', [TestimonialController::class, 'store']); 
    Route::get('/wa-template/{kategori}', [WaTemplateController::class, 'show']); 
 
    // ── Customer Lookup (NIK Check) ──────────────────── 
    Route::post('/customer/check-nik', [CustomerController::class, 'checkNik']); 
 
    // ── Booking Submission ───────────────────────────── 
    Route::post('/booking', [BookingController::class, 'store']); 
    Route::post('/booking/{id}/confirm-dp', [BookingController::class, 'confirmDp']); 
    Route::post('/booking/{id}/upload-dp-proof', [BookingController::class, 'uploadDpProof']);
    Route::post('/booking/{id}/upload-media', [MediaDocController::class, 'upload']); 
 
    // ── WhatsApp Link Generator ──────────────────────── 
    Route::post('/generate-wa-link', [WhatsAppController::class, 'generateLink']); 
}); 
