<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    public function deliveryForm()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $paymentTypes = PaymentType::all();

        return view('ecommerce.delivery', compact('cart', 'total', 'paymentTypes'));
    }

    public function finalizePurchase(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dni' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'payment_type_id' => 'required|exists:payment_types,id',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.checkout')->with('error', 'El carrito está vacío.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Crear la venta
        $sale = Sale::create([
            'user_id' => auth()->id(),
            'payment_type_id' => $request->payment_type_id,
            'total' => $total,
        ]);

        foreach ($cart as $productId => $item) {
            // Reducir stock
            $product = Product::find($productId);
            if ($product) {
                $product->stock = max(0, $product->stock - $item['quantity']);
                $product->save();
            }

            // Insertar en la tabla intermedia sale_detail
            $sale->products()->attach($productId, [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Limpiar carrito en base de datos (si existe)
        $cartModel = Cart::where('user_id', auth()->id())->first();
        if ($cartModel) {
            CartItem::where('cart_id', $cartModel->id)->delete();
            $cartModel->delete(); // si quieres eliminar también el cart en sí
        }

        // Datos de la empresa
        $empresa = [
            'nombre' => 'Pets S.A.C.',
            'ruc' => '20481234567',
            'direccion' => 'Av. Progreso 123, Chiclayo',
            'telefono' => '987654321',
        ];

        // Generar el PDF
        $pdf = PDF::loadView('ecommerce.voucher', [
            'sale' => $sale,
            'cart' => $cart,
            'cliente' => $request->only(['name', 'dni', 'phone', 'address']),
            'empresa' => $empresa,
        ]);

        // Guardar el archivo
        $fileName = "voucher_sale_{$sale->id}.pdf";
        $path = "vouchers/{$fileName}";
        Storage::disk('public')->put($path, $pdf->output());

        // Registrar en la tabla vouchers
        Voucher::create([
            'sale_id' => $sale->id,
            'path' => $path,
        ]);

        // Limpiar el carrito de sesión
        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Compra realizada con éxito.');
    }
}
