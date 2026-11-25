<?php

use App\Http\Controllers\Api\DeviceCheckController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.token')->group(function () {
    // Enregistrement / heartbeat du device (lancement, retour au premier plan
    // et heartbeat periodique tant que l'application est visible).
    Route::post('/devices/check', [DeviceCheckController::class, 'check']);

    // Signal de sortie : l'application n'est plus au premier plan.
    Route::post('/devices/disconnect', [DeviceCheckController::class, 'disconnect']);
});
