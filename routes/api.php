<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\AppointmentController;

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index')->middleware('role:manager');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show')->middleware('role:manager');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store')->middleware('role:admin');
    Route::patch('/clients/{client}', [ClientController::class, 'update'])->name('clients.update')->middleware('role:admin');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store')->middleware('role:manager');
    Route::post('/appointments/{appointment}/fill', [AppointmentController::class, 'fill'])->name('appointments.fill')->middleware('role:medic');
    Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update')->middleware('role:manager');

});
