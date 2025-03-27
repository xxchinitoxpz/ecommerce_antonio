<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PaymentTypeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\VoucherController;
use App\Models\Brand;
use Illuminate\Support\Facades\Route;

Route::resource('categories', CategoryController::class);

Route::resource('brands', BrandController::class);

Route::resource('products', ProductController::class);

Route::resource('vouchers', VoucherController::class);

Route::resource('paymentTypes', PaymentTypeController::class);

Route::resource('sales', SaleController::class);


