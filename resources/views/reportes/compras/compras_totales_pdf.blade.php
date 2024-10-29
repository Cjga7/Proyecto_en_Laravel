<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras Totales</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Compras Totales</h1>
    <h2>Año: {{ $anioSeleccionado }}, Mes: {{ $labels[$mesSeleccionado - 1] }}</h2>

    <table>
        <thead>
            <tr>
                <th>Día</th>
                <th>Total de Compras (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalComprasMes = 0; @endphp
            @foreach($comprasDelMesSeleccionado as $compra)
                <tr>
                    <td>{{ $compra->dia }}</td>
                    <td>{{ number_format($compra->total, 2) }} Bs</td>
                </tr>
                @php $totalComprasMes += $compra->total; @endphp
            @endforeach
            <tr>
                <th>Total del Mes</th>
                <th>{{ number_format($totalComprasMes, 2) }} Bs</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
