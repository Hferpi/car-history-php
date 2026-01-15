<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home');
Route::view('/login', 'login');
Route::view('/register', 'register');

Route::prefix('vehicles')->group(function () {
    Route::view('/', 'vehicles.index');      // /vehicles
    Route::view('/create', 'vehicles.create'); // /vehicles/create
    Route::view('/{id}', 'vehicles.show');   // /vehicles/{id}
});

Route::prefix('invoices')->group(function () {
    Route::view('/', 'invoices.index');
    Route::view('/create', 'invoices.create');
});

Route::view('/history', 'history');
