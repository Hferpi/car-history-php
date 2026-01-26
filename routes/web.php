<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;

// RUTAS PUBLICAS (Login y Registro)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', fn() => view('register'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// RUTAS PROTEGIDAS
Route::middleware('authcheck')->group(function () {

    //header y sus rutas
    Route::get('/', [VehicleController::class, 'home'])->name('home');
    Route::view('/history', 'history')->name('history');

    Route::get('/reparacion-proximamente', function() {
        return "Esta sección estará disponible próximamente.";
    })->name('vehicles.repair');


    // --- GRUPO DE VEHÍCULOS (EL GARAJE) ---
    Route::prefix('vehicles')->group(function () {

        //vista garage a traves de controlador
        Route::get('/', [VehicleController::class, 'index'])->name('vehicles.index');

        //form para crear coche
        Route::get('/create', [VehicleController::class, 'create'])->name('vehicles.create');

        //guardar coche en la bd
        Route::post('/', [VehicleController::class, 'store'])->name('vehicles.store');

        //vver detalle de vehiculo
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    });
});