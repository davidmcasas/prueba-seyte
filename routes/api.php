<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    Route::apiResource('/clients', App\Http\Controllers\API\ClientController::class);
    Route::apiResource('/appointments', App\Http\Controllers\API\AppointmentController::class);
});
