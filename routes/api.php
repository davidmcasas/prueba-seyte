<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {

    Route::apiResource('/clients', App\Http\Controllers\API\ClientController::class, [
        'only' => ['index', 'show', 'store', 'update']
    ]);

    Route::post('/appointments/{appointment}/fill', [App\Http\Controllers\API\AppointmentController::class, 'fill'])->name('appointments.fill');
    Route::apiResource('/appointments', App\Http\Controllers\API\AppointmentController::class, [
        'only' => ['index', 'show', 'store', 'update']
    ]);

});
