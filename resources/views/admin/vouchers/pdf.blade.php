<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="title">Boleta de Venta #{{ $sale->id }}</div>

    {{-- Datos de la empresa --}}
    <p><strong>Empresa:</strong> Pets S.A.C.</p>
    <p><strong>RUC:</strong> 20481234567</p>
    <p><strong>Dirección:</strong> Av. Progreso 123, Chiclayo</p>
    <p><strong>Teléfono:</strong> 987654321</p>

    {{-- Datos del cliente --}}
    <hr>
    <p><strong>Cliente:</strong> {{ $sale->user->name }}</p>
    <p><strong>DNI:</strong> {{ $sale->user->dni ?? '—' }}</p>
    <p><strong>Dirección:</strong> {{ $sale->user->address ?? '—' }}</p>
    <p><strong>Teléfono:</strong> {{ $sale->user->phone ?? '—' }}</p>

    <p><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Tipo de pago:</strong> {{ $sale->paymentType->name }}</p>


    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>P. Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>S/. {{ number_format($product->pivot->price, 2) }}</td>
                    <td>S/. {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: right; margin-top: 10px;"><strong>Total:</strong> S/. {{ number_format($sale->total, 2) }}</p>
</body>

</html>
