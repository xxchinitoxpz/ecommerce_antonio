<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['user', 'paymentType', 'voucher'])->orderBy('id', 'desc')->get();
        return view('admin.sales.index', compact('sales'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $paymentTypes = PaymentType::all();

        return view('admin.sales.create', compact('products', 'paymentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'products_json' => 'required|string',
        ]);

        $products = json_decode($data['products_json'], true);

        $total = collect($products)->sum(fn($p) => $p['price'] * $p['quantity']);

        $sale = Sale::create([
            'user_id' => auth()->id(),
            'payment_type_id' => $data['payment_type_id'],
            'total' => $total,
        ]);

        foreach ($products as $item) {
            $product = Product::findOrFail($item['id']);

            // Validación de stock
            if ($item['quantity'] > $product->stock) {
                abort(400, "No hay suficiente stock para el producto '{$product->name}'.");
            }

            // Asociar el producto con la venta
            $sale->products()->attach($product->id, [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Reducir el stock
            $product->decrement('stock', $item['quantity']);
        }


        $pdf = Pdf::loadView('admin.vouchers.pdf', ['sale' => $sale]);

        // Nombre del archivo
        $filename = 'voucher_sale_' . $sale->id . '.pdf';
        $path = 'vouchers/' . $filename;

        // Guardar en storage/app/public/vouchers
        Storage::disk('public')->put($path, $pdf->output());

        // Crear registro en la tabla vouchers
        Voucher::create([
            'sale_id' => $sale->id,
            'path' => $path,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Venta registrada!',
            'text' => 'La venta fue guardada correctamente.',
        ]);

        return redirect()->route('admin.sales.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $sale->load(['products', 'voucher']); // Cargar productos existentes
        $products = Product::all();
        $paymentTypes = PaymentType::all();

        return view('admin.sales.edit', compact('sale', 'products', 'paymentTypes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'products_json' => 'required|string',
        ]);

        $products = json_decode($data['products_json'], true);

        DB::transaction(function () use ($sale, $products, $data) {
            // 1. Restaurar stock anterior
            foreach ($sale->products as $p) {
                $p->increment('stock', $p->pivot->quantity);
            }

            // 2. Quitar productos actuales
            $sale->products()->detach();

            // 3. Calcular nuevo total
            $total = collect($products)->sum(fn($p) => $p['quantity'] * $p['price']);

            // 4. Actualizar venta
            $sale->update([
                'payment_type_id' => $data['payment_type_id'],
                'total' => $total,
            ]);

            // 5. Asociar nuevos productos y descontar stock
            foreach ($products as $item) {
                $product = Product::findOrFail($item['id']);

                if ($item['quantity'] > $product->stock) {
                    abort(400, "No hay suficiente stock para el producto '{$product->name}'.");
                }

                $sale->products()->attach($product->id, [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // 6. Regenerar boleta PDF
            if ($sale->voucher && Storage::disk('public')->exists($sale->voucher->path)) {
                Storage::disk('public')->delete($sale->voucher->path);
            }

            $pdf = Pdf::loadView('admin.vouchers.pdf', ['sale' => $sale->fresh('products', 'user', 'paymentType')]);
            $filename = 'voucher_sale_' . $sale->id . '.pdf';
            $path = 'vouchers/' . $filename;
            Storage::disk('public')->put($path, $pdf->output());

            $sale->voucher()->updateOrCreate([], [
                'path' => $path,
            ]);
        });

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Venta actualizada!',
            'text' => 'Los cambios se han guardado correctamente.',
        ]);

        return redirect()->route('admin.sales.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Cargar relaciones necesarias
        $sale->load(['products', 'voucher']);

        DB::transaction(function () use ($sale) {
            // 1. Restaurar stock
            foreach ($sale->products as $product) {
                $product->increment('stock', $product->pivot->quantity);
            }

            // 2. Eliminar registros de la tabla pivot sale_detail
            $sale->products()->detach();

            // 3. Eliminar PDF si existe
            if ($sale->voucher && Storage::disk('public')->exists($sale->voucher->path)) {
                Storage::disk('public')->delete($sale->voucher->path);
            }

            // 4. Eliminar registro del voucher
            if ($sale->voucher) {
                $sale->voucher()->delete();
            }

            // 5. Eliminar la venta
            $sale->delete();
        });

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Venta eliminada!',
            'text' => 'El stock fue restaurado y la boleta eliminada.',
        ]);

        return redirect()->route('admin.sales.index');
    }
}
