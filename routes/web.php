<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleRepairController;

// Públicas
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', fn () => view('register'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protegidas
Route::middleware('authcheck')->group(function () {

    Route::get('/', fn () => view('home'))->name('home');
    Route::get('/history', fn () => view('history'))->name('history');

    // 🚗 VEHÍCULOS
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    });

    // 🔧 REPARACIONES (ligadas a vehículo)
Route::post(
    '/vehicles/{vehicle}/repairs',
    [VehicleRepairController::class, 'store']
)->name('repairs.store');
Route::get(
    '/vehicles/{vehicle}/repair',
    fn ($vehicle) => view('vehicles.repair', compact('vehicle'))
)->name('vehicles.repair');

});
