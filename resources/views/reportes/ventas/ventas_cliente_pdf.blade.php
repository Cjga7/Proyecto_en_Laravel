<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas por Cliente</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
            background-color: #f9f9f9;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        header img {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
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
        }
        .page-number:after {
            content: counter(page);
        }
        main {
            margin-top: 20px;
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
        <h1>Reporte de Ventas por Cliente</h1>
        <p>Fecha de impresión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        <p>Año: {{ $anio }} @if($mes) | Mes: {{ \Carbon\Carbon::create()->month($mes)->translatedFormat('F') }} @endif</p>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Total Comprado</th>
                    <th>Total Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $venta->cliente }}</td>
                        <td>{{ $venta->total_comprado }}</td>
                        <td>{{ number_format($venta->total_ingresos, 2) }} Bs.</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <footer>
        Página <span class="page-number"></span>
    </footer>
</body>
</html>
