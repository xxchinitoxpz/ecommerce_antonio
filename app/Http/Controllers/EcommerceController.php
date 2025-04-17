<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('ecommerce.index', compact('products'));
    }

    public function products(Request $request)
    {
        $query = Product::query();

        // Filtrar por categoría si se proporciona
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtrar por marca si se proporciona
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Solo productos con stock > 0
        $query->where('stock', '>', 0);

        $products = $query->paginate(8); // Paginación de 8 productos

        $categories = Category::all();
        $brands = Brand::all();

        return view('ecommerce.products', compact('products', 'categories', 'brands'));
    }
}
