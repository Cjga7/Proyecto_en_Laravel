<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Inventario</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Presentación</th>
                <th>Registro Sanitario</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>
                        @foreach ($producto->categorias as $categoria)
                            {{ $categoria->caracteristica->nombre ?? 'N/A' }}
                        @endforeach
                    </td>
                    <td>{{ $producto->presentacione->caracteristica->nombre ?? 'N/A' }}</td>
                    <td>{{ $producto->registrosanitario->caracteristica->nombre ?? 'N/A' }}</td>
                    <td>{{ $producto->tipo_producto_id == 1 ? 'Terminado' : 'Materia Prima' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
