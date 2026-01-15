<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rutas públicas (sin middleware)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Rutas protegidas por login
Route::middleware('authcheck')->group(function () {
    Route::view('/', 'home')->name('home');
    Route::view('/register', 'register')->name('register');
    Route::view('/history', 'history')->name('history');

    Route::prefix('vehicles')->group(function () {
        Route::view('/', 'vehicles.index')->name('vehicles.index');
        Route::view('/create', 'vehicles.create')->name('vehicles.create');
        Route::view('/{id}', 'vehicles.show')->name('vehicles.show');
    });

    Route::prefix('invoices')->group(function () {
        Route::view('/', 'invoices.index')->name('invoices.index');
        Route::view('/create', 'invoices.create')->name('invoices.create');
    });

});
