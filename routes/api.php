<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExceptionController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CARGOTRACE REST API ROUTES (Slide 12 & 13)
| Protected with Laravel Sanctum & Role-Based Access Control
|--------------------------------------------------------------------------
*/

// 1. PUBLIC AUTHENTICATION & MASTER DATA
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::get('/carriers', [MasterDataController::class, 'carriers'])->name('api.carriers.index');
Route::get('/locations', [MasterDataController::class, 'locations'])->name('api.locations.index');

// 2. PROTECTED ROUTES (Bearer Token Required)
Route::middleware('auth:sanctum')->group(function () {

    // Auth profile & logout
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // Shipments (Customer automatically scoped to own, Admin/Operator sees all)
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('api.shipments.index');
    Route::get('/shipments/{id}', [ShipmentController::class, 'show'])->name('api.shipments.show');

    // Tracking events timeline & operational exceptions
    Route::get('/shipments/{id}/tracking', [TrackingController::class, 'index'])->name('api.tracking.index');
    Route::get('/shipments/{id}/exceptions', [ExceptionController::class, 'index'])->name('api.exceptions.index');

    // 3. OPERATIONAL WRITE ACCESS (Admin & Operator Only - Slide 5, 6, 8, 13)
    Route::middleware('role:admin,operator')->group(function () {
        Route::post('/shipments', [ShipmentController::class, 'store'])->name('api.shipments.store');
        Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->name('api.shipments.update');
        Route::post('/shipments/{id}/tracking', [TrackingController::class, 'store'])->name('api.tracking.store');
        Route::post('/shipments/{id}/exceptions', [ExceptionController::class, 'store'])->name('api.exceptions.store');
    });

});
