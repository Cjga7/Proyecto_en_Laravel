<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ventas - PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Historial de Ventas</h1>
    <h2>
        @if ($producto)
            Producto: {{ $producto->nombre }} (ID: {{ $producto->id }})
        @else
            Todos los productos
        @endif
    </h2>

    <p>Mes: {{ $mes ? \Carbon\Carbon::create()->month($mes)->translatedFormat('F') : 'Todos' }}</p>
    <p>Año: {{ $anio ?? 'Todos' }}</p>

    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha de Venta</th>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
                <th>Precio de Venta</th>
                <th>Total Venta</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historial as $venta)
                @foreach ($venta->productos as $producto)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->isoFormat('D [de] MMMM [de] YYYY, H:mm') }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->pivot->cantidad }}</td>
                        <td>{{ number_format($producto->pivot->precio_venta, 2) }} Bs</td>
                        <td>{{ number_format($producto->pivot->cantidad * $producto->pivot->precio_venta, 2) }} Bs</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <footer>
        <p style="text-align: center; margin-top: 20px;">Generado el {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM [de] YYYY, H:mm') }}</p>
    </footer>

</body>
</html>
