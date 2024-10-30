<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ventas - PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }
        header h2 {
            margin: 0;
            font-size: 18px;
            color: #777;
        }
        header p {
            margin: 0;
            font-size: 14px;
            color: #777;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
            padding: 10px 0;
        }
        main {
            margin-top: 20px;
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

    <header>
        <h1>Historial de Ventas</h1>
        <h2>
            @if ($producto)
                Producto: {{ $producto->nombre }} (ID: {{ $producto->id }})
            @else
                Todos los productos
            @endif
        </h2>
        <p>Desde: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</p>
        <p>Hasta: {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    </header>

    <main>
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
    </main>

    <footer>
        <p style="text-align: center; margin-top: 20px;">Generado el {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM [de] YYYY, H:mm') }}</p>
        <p>Lanago - Ventas Naturales</p>
        <p>Contacto: info@lanago.com | Teléfono: +591 123 456 789</p>
        Página <span class="page-number"></span>
    </footer>

</body>
</html>
