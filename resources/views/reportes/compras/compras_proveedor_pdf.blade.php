<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras por Proveedor</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Compras por Proveedor</h2>
    <p><strong>Año:</strong> {{ $anio ?? 'Todos' }} | <strong>Mes:</strong> {{ $mes ? \Carbon\Carbon::create()->month($mes)->translatedFormat('F') : 'Todos' }}</p>

    <table>
        <thead>
            <tr>
                <th>Proveedor</th>
                <th>Total Compras (Bs)</th>
                <th>Total Productos Comprados</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compras as $compra)
                <tr>
                    <td>{{ $compra->proveedor }}</td>
                    <td>{{ number_format($compra->total_compras, 2) }} Bs</td>
                    <td>{{ $compra->total_productos_comprados }}</td>
                    <td>{{ $compra->productos }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
