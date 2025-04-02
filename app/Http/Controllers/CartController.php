<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Sesión
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price - $product->discount,
                'image' => $product->image,
                'quantity' => 1
            ];
        }
        session()->put('cart', $cart);

        // Base de datos solo si está logueado
        if (Auth::check()) {
            $user = Auth::user();
            $userCart = $user->cart ?? Cart::create(['user_id' => $user->id]);

            $item = $userCart->items()->where('product_id', $product->id)->first();
            if ($item) {
                $item->increment('quantity');
            } else {
                $userCart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price - $product->discount
                ]);
            }
        }

        return response()->json(['message' => 'Producto agregado al carrito']);
    }

    public function json()
    {
        $cart = session('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            $items[] = [
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subtotal
            ];
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('ecommerce.checkout', compact('cart', 'total'));
    }
}
