<!DOCTYPE html>
<html>
<head>
    <title>Boleta de Venta</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Boleta de Venta</h2>

    <p><strong>Empresa:</strong> {{ $empresa['nombre'] }}</p>
    <p><strong>RUC:</strong> {{ $empresa['ruc'] }}</p>
    <p><strong>Dirección:</strong> {{ $empresa['direccion'] }}</p>
    <p><strong>Teléfono:</strong> {{ $empresa['telefono'] }}</p>

    <hr>

    <h4>Datos del Cliente</h4>
    <p><strong>Nombre:</strong> {{ $cliente['name'] }}</p>
    <p><strong>DNI:</strong> {{ $cliente['dni'] }}</p>
    <p><strong>Teléfono:</strong> {{ $cliente['phone'] }}</p>
    <p><strong>Dirección:</strong> {{ $cliente['address'] }}</p>

    <hr>

    <h4>Detalle de la compra</h4>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>P/U</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> ${{ number_format($sale->total, 2) }}</p>
</body>
</html>
