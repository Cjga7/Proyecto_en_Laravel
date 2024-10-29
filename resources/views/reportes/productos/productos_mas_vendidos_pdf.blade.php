<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos Más Vendidos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Productos Más Vendidos</h1>
    <p><strong>Año:</strong> {{ $anio }} <strong>Mes:</strong> {{ $mes }}</p>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Total Vendido</th>
                <th>Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->total_vendido }}</td>
                    <td>{{ number_format($producto->ingresos, 2) }} Bs</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
