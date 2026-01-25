<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;

// RUTAS PUBLICAS (Login y Registro)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () { return view('register'); })->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// RUTAS PROTEGIDAS
Route::middleware('authcheck')->group(function () {

    // Inicio e Historial
    Route::view('/', 'home')->name('home');
    Route::view('/history', 'history')->name('history');

    // --- GRUPO DE VEHÍCULOS (EL GARAJE) ---
    Route::prefix('vehicles')->group(function () {

        // Listado de vehículos (Vista garage.blade.php a través del controlador)
        Route::get('/', [VehicleController::class, 'index'])->name('vehicles.index');

        // Formulario para crear un coche
        Route::get('/create', [VehicleController::class, 'create'])->name('vehicles.create');

        // Guardar el coche en la base de datos
        Route::post('/', [VehicleController::class, 'store'])->name('vehicles.store');

        // Ver detalle de un vehículo o reparaciones
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    });

    // --- GRUPO DE FACTURAS ---
    Route::prefix('invoices')->group(function () {
        Route::view('/', 'invoices.index')->name('invoices.index');
        Route::view('/create', 'invoices.create')->name('invoices.create');
    });

});