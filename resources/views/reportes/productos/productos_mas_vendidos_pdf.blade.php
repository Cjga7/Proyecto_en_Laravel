<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos Más Vendidos</title>
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
        header img {
            position: absolute;
            left: 0;
            top: -20px; /* Ajusta la posición del logo hacia arriba */
            width: 100px;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }
        header p {
            margin: 5px 0;
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
        .page-number:after {
            content: counter(page);
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
        <img src="assets/images/Logo_lanago.png" alt="Logo Lanago">
        <h1>Reporte de Productos Más Vendidos</h1>
        <p><strong>Desde:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    </header>

    <main>
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
    </main>

    <footer>
        <p>Lanago - Ventas Naturales</p>
        <p>Contacto: info@lanago.com | Teléfono: +591 123 456 789</p>
        Página <span class="page-number"></span>
    </footer>
</body>
</html>
