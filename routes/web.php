<?php

use App\Http\Controllers\CarrierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

// 1. Landing Page (Halaman Depan)
Route::get('/', function () {
    return view('welcome');
});

// Public Tracking & Cetak Resi Publik (Slide 10 - No login required)
Route::get('/track/{tracking_number?}', [ShipmentController::class, 'publicTrack'])->name('shipments.public-track');
Route::get('/track/{tracking_number}/print', [ShipmentController::class, 'publicPrintReceipt'])->name('shipments.public-print');

// 2. Rute yang Membutuhkan Login (Auth)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // MODULE SHIPMENTS (Semua Role bisa akses sesuai izinnya - Slide 5)
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create')->middleware('role:admin,operator');
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store')->middleware('role:admin,operator');
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::get('/shipments/{shipment}/print', [ShipmentController::class, 'printReceipt'])->name('shipments.print');

    // Tracking Event & Kendala Exception (Hanya Admin & Operator)
    Route::post('/shipments/{shipment}/tracking', [ShipmentController::class, 'storeTracking'])->name('shipments.tracking.store')->middleware('role:admin,operator');
    Route::post('/shipments/{shipment}/exceptions', [ShipmentController::class, 'storeException'])->name('shipments.exceptions.store')->middleware('role:admin,operator');

    // Payment Gateway (Simulasi Pembayaran Tagihan Kargo)
    Route::post('/shipments/{shipment}/pay', [ShipmentController::class, 'pay'])->name('shipments.pay');

    // MODULE CARRIERS & LOCATIONS (Khusus Administrator - Slide 5)
    Route::resource('carriers', CarrierController::class)->only(['index', 'store'])->middleware('role:admin');
    Route::resource('locations', LocationController::class)->only(['index', 'store'])->middleware('role:admin');
});

require __DIR__.'/auth.php';