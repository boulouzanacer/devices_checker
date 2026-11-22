<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RevendeurController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/devices');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('devices', DeviceController::class);
    Route::post('/devices/{device}/toggle-block', [DeviceController::class, 'toggleBlock'])->name('devices.toggle-block');

    Route::get('/revendeurs', [RevendeurController::class, 'index'])->name('revendeurs.index');

    Route::get('/historique', [HistoryController::class, 'index'])->name('history.index');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
