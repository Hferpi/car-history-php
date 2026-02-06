<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleRepairController;
use App\Http\Controllers\OCRextractInfo;

// RUTAS PUBLICAS (Login y Registro)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', fn() => view('register'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// RUTAS PROTEGIDAS
Route::middleware('authcheck')->group(function () {

    // Header y sus rutas
    Route::get('/', [VehicleController::class, 'home'])->name('home');
    Route::get('/history', [VehicleController::class, 'history'])->name('history');

    // --- GRUPO DE VEHÍCULOS (EL GARAJE) ---
    Route::prefix('vehicles')->group(function () {

        // Vista garage
        Route::get('/garaje', [VehicleController::class, 'index'])
            ->name('garaje');

        // Form para crear coche
        Route::get('/create', [VehicleController::class, 'create'])->name('vehicles.create');

        // Guardar coche en la bd
        Route::post('/', [VehicleController::class, 'store'])->name('vehicles.store');

        // Ver detalle de vehiculo
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

        // Seleccionar coche para ver en home
        Route::post('/select', [VehicleController::class, 'select'])->name('vehicles.select');

        // --- REPARACIONES (dentro del grupo vehicles) ---
        Route::prefix('{vehicle}/repairs')->group(function () {

            // Mostrar formulario de reparación (GET)
            Route::get('/', [VehicleRepairController::class, 'create'])
                ->name('vehicles.repairs.create');  // ← Cambiado para consistencia

            // Procesar OCR (POST)
            Route::post('/ocr', [OCRextractInfo::class, 'extract'])
                ->name('vehicles.repairs.ocr');  // ← Ahora está dentro del grupo

            // Guardar reparación (POST)
            Route::post('/', [VehicleRepairController::class, 'store'])
                ->name('repairs.store');
        });
    });
});
