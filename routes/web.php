<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing Page 
Route::get('/', [LandingController::class, 'index'])->name('home'); 
Route::post('/set-language', [LandingController::class, 'setLanguage'])->name('set.lang'); 
Route::get('/fleet', [LandingController::class, 'fleetAll'])->name('fleet.all');
Route::get('/gallery', [LandingController::class, 'galleryAll'])->name('gallery.all');
Route::get('/news', [LandingController::class, 'newsAll'])->name('news.all');
Route::get('/vehicle/{id}', [LandingController::class, 'vehicleDetail'])->name('vehicle.detail');
Route::get('/news/{slug}', [LandingController::class, 'newsDetail'])->name('news.detail');
 
// Booking Flow (Form Pages) 
Route::prefix('booking')->name('booking.')->group(function () { 
    Route::get('/sewa-mobil', [BookingPageController::class, 'sewa'])->name('sewa'); 
    Route::get('/city-tour', [BookingPageController::class, 'tour'])->name('tour'); 
    Route::get('/travel', [BookingPageController::class, 'travel'])->name('travel'); 
    Route::get('/qris/{code}', [BookingPageController::class, 'qris'])->name('qris'); 
    Route::get('/success/{code}', [BookingPageController::class, 'success'])->name('success'); 
    Route::get('/track/{code}', [BookingPageController::class, 'track'])->name('track');
    Route::get('/testimonial/{code}', [BookingPageController::class, 'testimonial'])->name('testimonial');
});

Route::get('/dashboard', function () {
    return inertia('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Redirect default login to admin login
Route::get('/login', function () {
    return redirect()->route('admin.login');
});
