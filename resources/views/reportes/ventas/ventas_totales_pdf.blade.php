<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - {{ $mesSeleccionado }}/{{ $anio }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        header img {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
        }
        header h1 {
            margin: 0;
            color: #4CAF50;
            font-size: 24px;
        }
        header h2 {
            margin: 0;
            color: #777;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .page-number:after {
            content: counter(page);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .totales-mes h3 {
            margin-top: 20px;
            color: #4CAF50;
        }
        .totales-mes table {
            width: 100%;
            border-collapse: collapse;
        }
        .totales-mes th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="assets/images/Logo_lanago.png" alt="Logo Lanago" class="logo">
            <h1>Reporte de Ventas</h1>
            <h2>Mes: {{ \Carbon\Carbon::create()->month($mesSeleccionado)->translatedFormat('F') }} - Año: {{ $anio }}</h2>
            <p>Fecha de impresión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </header>

        <h3>Ventas del Mes Seleccionado</h3>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Total Ventas (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ventasDelMesSeleccionado as $venta)
                    <tr>
                        <td>{{ $venta->dia }}</td>
                        <td>{{ number_format($venta->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No hay ventas registradas para este mes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totales-mes">
            <h3>Resumen Total de Ventas por Mes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Total Ventas (Bs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labels as $index => $mes)
                        <tr style="background-color: {{ $colores[$index] }};">
                            <td>{{ $mes }}</td>
                            <td>{{ number_format($datosVentas[$index], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <footer>
            <p>Lanago - Ventas Naturales</p>
            <p>Contacto: info@lanago.com | Teléfono: +591 123 456 789</p>
            Página <span class="page-number"></span>
        </footer>
    </div>
</body>
</html>
