<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - {{ $fechaInicio }} a {{ $fechaFin }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Asegura que el body ocupe al menos la altura de la ventana */
        }
        header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
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
            padding: 10px 0; /* Espaciado en el footer */
        }
        .page-number:after {
            content: counter(page);
        }
        main {
            margin-top: 20px;
            flex: 1; /* Permite que el contenido crezca y empuje el footer hacia abajo */
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
        <h1>Reporte de Ventas</h1>
        <h2>Del {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</h2>
        <p>Fecha de impresión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </header>

    <main>
        <h3>Ventas Totales por Rango de Fechas</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Total Ventas (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ventas as $venta)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                        <td>{{ number_format($venta->total, 2) }} Bs.</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No hay ventas registradas para el período seleccionado.</td>
                    </tr>
                @endforelse
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
