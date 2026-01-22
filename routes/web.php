<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehiculoController;

// Rutas públicas (sin middleware)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () { return view('register'); })->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Rutas protegidas por login
Route::middleware('authcheck')->group(function () {
    Route::view('/', 'home')->name('home');
    Route::view('/history', 'history')->name('history');

    Route::prefix('vehicles')->group(function () {
        Route::view('/', 'vehicles.index')->name('vehicles.index');
        Route::view('/create', 'vehicles.create')->name('vehicles.create');
        Route::view('/{id}', 'vehicles.show')->name('vehicles.show');
    });

    // Es recomendable que esta ruta esté protegida por el middleware 'auth'
    Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');

    Route::prefix('invoices')->group(function () {
        Route::view('/', 'invoices.index')->name('invoices.index');
        Route::view('/create', 'invoices.create')->name('invoices.create');
    });

});


// Rutas para coches modelo y marca
