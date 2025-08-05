<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Houses management
    Route::resource('houses', HouseController::class);
    
    // Residents management
    Route::resource('residents', ResidentController::class);
    
    // Payments management
    Route::resource('payments', PaymentController::class);
    
    // Complaints management
    Route::resource('complaints', ComplaintController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';