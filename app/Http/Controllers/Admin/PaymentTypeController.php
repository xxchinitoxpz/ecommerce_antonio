<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{


    public function index()
    {
        $paymentType = PaymentType::orderBy('id', 'desc')->get();
        return view('admin.paymentType.index', compact('paymentType'));
    }

    public function create()
    {
        return view('admin.paymentType.create');
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PaymentType::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'Tipo de pago creado correctamente.'
        ]);

        return redirect()->route('admin.paymentTypes.index');
    }

    public function edit(PaymentType $paymentType)
    {
        return view('admin.paymentType.edit', compact('paymentType'));
    }

    public function update(Request $request, PaymentType $paymentType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $paymentType->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Actualizado!',
            'text' => 'Tipo de pago actualizado correctamente.'
        ]);

        return redirect()->route('admin.paymentTypes.index');
    }

    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Eliminado!',
            'text' => 'Tipo de pago eliminado correctamente.'
        ]);

        return redirect()->route('admin.paymentTypes.index');
    }
}
