<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    public function index()
    {
        $products = Product::all(); // o lo que quieras mostrar
        return view('ecommerce.index', compact('products'));
    }
}
