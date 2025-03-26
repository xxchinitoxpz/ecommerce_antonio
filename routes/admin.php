<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Brand;
use Illuminate\Support\Facades\Route;

Route::resource('categories', CategoryController::class);

Route::resource('brands', BrandController::class);

Route::resource('products', ProductController::class);

