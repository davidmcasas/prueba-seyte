<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    } else if (auth()->user()->isMedic()) {
        return redirect()->route('appointments');
    } else {
        return redirect()->route('clients');
    }
})->name('dashboard');

Route::view('clientes', 'clients')->middleware(['auth', 'verified', 'role:manager'])->name('clients');
Route::view('citas', 'appointments')->middleware(['auth', 'verified'])->name('appointments');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
