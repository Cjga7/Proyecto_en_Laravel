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
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3 {
            text-align: center;
            margin: 0;
            color: #333;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .totales-mes {
            margin-top: 30px;
        }
        .totales-mes h3 {
            margin-top: 20px;
        }
        .totales-mes table {
            border: 1px solid #000;
        }
        .totales-mes th {
            background-color: #4CAF50;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #555;
        }
        img.logo {
            display: block;
            margin: 0 auto 10px auto;
            width: 150px; /* Cambiar según el tamaño del logotipo */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado del Reporte -->
        <div class="header">
            <img src="ruta/del/logo.png" alt="Logo Lanago" class="logo"> <!-- Reemplaza con la ruta de tu logo -->
            <h1>Reporte de Ventas</h1>
            <h2>Mes: {{ \Carbon\Carbon::create()->month($mesSeleccionado)->translatedFormat('F') }} - Año: {{ $anio }}</h2>
        </div>

        <!-- Tabla de ventas del mes seleccionado -->
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

        <!-- Totales por mes en una tabla resumen -->
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

        <!-- Pie de página -->
        <div class="footer">
            <p>Lanago - Ventas Naturales</p>
            <p>Contacto: info@lanago.com | Teléfono: +591 123 456 789</p>
        </div>
    </div>
</body>
</html>
