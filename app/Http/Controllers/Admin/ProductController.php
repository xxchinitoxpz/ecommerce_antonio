<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'discount' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['discount'] = $data['discount'] ?? 0;

        // 1. Crear producto sin imagen aún
        $product = Product::create(collect($data)->except('image')->toArray());

        // 2. Si hay imagen, renombrarla con el ID del producto
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $product->id . '.' . $extension;

            $path = $file->storeAs('products_img', $filename, 'public');

            // 3. Actualizar producto con ruta de imagen
            $product->update(['image' => $path]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El producto se ha creado correctamente'
        ]);

        return redirect()->route('admin.products.index');
    }



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'discount' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['discount'] = $data['discount'] ?? 0;

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $product->id . '.' . $extension;

            $path = $request->file('image')->storeAs('products_img', $filename, 'public');

            $data['image'] = $path;
        }

        $product->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Actualizado!',
            'text' => 'El producto se ha actualizado correctamente'
        ]);

        return redirect()->route('admin.products.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Eliminar imagen si existe
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El producto se ha eliminado correctamente'
        ]);

        return redirect()->route('admin.products.index');
    }
}
