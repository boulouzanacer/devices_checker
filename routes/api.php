<?php

use App\Http\Controllers\Api\DeviceCheckController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.token')->group(function () {
    Route::post('/devices/check', [DeviceCheckController::class, 'check']);
});
