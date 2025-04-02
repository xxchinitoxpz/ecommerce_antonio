<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get();
        return view('ecommerce.index', compact('products'));
    }

    public function products()
    {
        $products = Product::all();
        return view('ecommerce.products', compact('products'));
    }
}
