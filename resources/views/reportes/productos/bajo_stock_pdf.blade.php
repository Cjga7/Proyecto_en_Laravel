<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos con Bajo Stock</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte de Productos con Bajo Stock</h1>

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
                    <td>{{$producto->tipo_producto_id == 1 ? 'Terminado' : 'Materia Prima' }}</td>
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
</body>
</html>
