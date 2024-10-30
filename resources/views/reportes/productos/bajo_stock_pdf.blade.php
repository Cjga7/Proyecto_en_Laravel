<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos con Bajo Stock</title>
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
            font-size: 12px;
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
            margin-top: 60px; /* Asegura espacio para el header */
            flex: 1;
            overflow: auto; /* Permite el desplazamiento si es necesario */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 5px;
            margin-top: 20px;
            table-layout: fixed; /* Fija el ancho de la tabla */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px; /* Aumenta el espaciado de la tabla */
            text-align: left;
            word-wrap: break-word; /* Permite que el contenido largo se ajuste */
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
        <h1>Reporte de Productos con Bajo Stock</h1>
        <p>Fecha de impresión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p> <!-- Fecha de impresión -->
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo de producto</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio de Venta</th>
                    <th>Descripción</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Estado</th>
                    <th>Registro Sanitario</th>
                    <th>Presentación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->tipo_producto_id == 1 ? 'Terminado' : 'Materia Prima' }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->precio_venta ? number_format($producto->precio_venta, 2) . ' Bs' : 'N/A' }}</td>
                        <td>{{ $producto->descripcion ?? 'No disponible' }}</td>
                        <td>{{ $producto->fecha_vencimiento ? \Carbon\Carbon::parse($producto->fecha_vencimiento)->format('d/m/Y') : 'No disponible' }}</td>
                        <td>{{ $producto->estado ? 'Activo' : 'Inactivo' }}</td>
                        <td>{{ $producto->registrosanitario->caracteristica->nombre ?? 'No disponible' }}</td>
                        <td>{{ $producto->presentacione->caracteristica->nombre ?? 'No disponible' }}</td>
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
