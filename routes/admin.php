<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\PricingController as AdminPricingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\WaTemplateController as AdminWaTemplateController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;

// Auth 
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login'); 
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post'); 
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout'); 
 
Route::middleware(['auth:admin', 'admin.access', 'throttle:300,1'])->prefix('admin')->name('admin.')->group(function () { 
 
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); 
 
    // Armada 
    Route::resource('vehicles', AdminVehicleController::class); 
    Route::post('/vehicles/{id}/toggle-status', [AdminVehicleController::class, 'toggleStatus']); 
    Route::post('/vehicles/{id}/upload-photo', [AdminVehicleController::class, 'uploadPhoto']); 
 
    // Booking 
    Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    Route::resource('bookings', AdminBookingController::class)->only(['index','show','update']); 
    Route::post('/bookings/{id}/update-status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status'); 
    Route::post('/bookings/{id}/verify', [AdminBookingController::class, 'verify'])->name('bookings.verify'); 
 
    // Customer 
    Route::get('/customers/export', [AdminCustomerController::class, 'export'])->name('customers.export');
    Route::resource('customers', AdminCustomerController::class)->only(['index','show','update']); 
    Route::post('/customers/{nik}/blacklist', [AdminCustomerController::class, 'blacklist']); 
    Route::post('/customers/{nik}/unblacklist', [AdminCustomerController::class, 'unblacklist']); 
 
    // CMS Content 
    Route::get('/content', [AdminContentController::class, 'index'])->name('content'); 
    Route::put('/content/{section}', [AdminContentController::class, 'update'])->name('content.update'); 
    Route::post('/content/upload-qris', [AdminContentController::class, 'uploadQris'])->name('content.upload-qris'); 

    // Harga & Promo 
    Route::get('pricing/template', [AdminPricingController::class, 'downloadTemplate'])->name('pricing.template');
    Route::post('pricing/import', [AdminPricingController::class, 'import'])->name('pricing.import');
    Route::put('pricing/service-types', [AdminPricingController::class, 'updateServiceTypes'])->name('pricing.service-types');
    Route::resource('pricing', AdminPricingController::class); 

    // Testimoni 
    Route::resource('testimonials', AdminTestimonialController::class); 

    // Berita / Posts
    Route::resource('posts', AdminPostController::class);

    // Galeri
    Route::resource('gallery', AdminGalleryController::class);

    // WA Templates 
    Route::resource('wa-templates', AdminWaTemplateController::class)->only(['index','update']); 
    Route::put('wa-templates/settings/whatsapp-number', [AdminWaTemplateController::class, 'updateWhatsappNumber'])->name('wa-templates.settings.whatsapp-number');

    // Analitik 
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics'); 
    Route::get('/analytics/data', [AdminAnalyticsController::class, 'data'])->name('analytics.data'); 
}); 
