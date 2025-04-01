<?php

use App\Http\Controllers\EcommerceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return redirect('/ecommerce/index.blade.php');
// })->name('home');

Route::get('/', [EcommerceController::class, 'index'])->name('home');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
