<?php

use App\Http\Controllers\EcommerceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\CartController;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return redirect('/ecommerce/index.blade.php');
// })->name('home');

Route::get('/', [EcommerceController::class, 'index'])->name('home');
Route::get('/products', [EcommerceController::class, 'products'])->name('products');
Route::get('/cart/content', [CartController::class, 'content'])->name('cart.content');

Route::get('/cart/json', [CartController::class, 'json'])->name('cart.json');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/checkout/info', [CartController::class, 'deliveryForm'])->name('cart.delivery');
Route::post('/checkout/finalize', [CartController::class, 'finalizePurchase'])->name('cart.finalize');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});



Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

require __DIR__.'/auth.php';
