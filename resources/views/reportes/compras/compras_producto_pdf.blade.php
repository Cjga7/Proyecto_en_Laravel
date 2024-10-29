<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compras por Producto - {{ $mes }}/{{ $anio }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="title">Compras por Producto - {{ $mes }}/{{ $anio }}</div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Total Comprado</th>
                <th>Total Gastado (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compras as $compra)
                <tr>
                    <td>{{ $compra->producto }}</td>
                    <td>{{ $compra->total_comprado }}</td>
                    <td>{{ number_format($compra->total_gasto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
