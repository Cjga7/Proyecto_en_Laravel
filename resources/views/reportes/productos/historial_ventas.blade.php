@extends('layouts.master')

@section('title', 'Historial de Ventas del Producto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Historial de Ventas del Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.productos.index') }}">Reportes de Productos</a></li>
        <li class="breadcrumb-item active">Historial de Ventas</li>
    </ol>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    Historial de Ventas del Producto
                    @if ($productoId === 'all')
                        (Todos los productos)
                    @else
                        ID: {{ $productoId }}
                    @endif
                </div>
                <div class="card-body">

                    <!-- Formulario para seleccionar rango de fechas -->
                    <form action="{{ route('reportes.productos.historial', $productoId) }}" method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                            </div>
                        </div>

                        <!-- Botones de filtro y generación de PDF -->
                        <div class="col-md-12 d-flex align-items-end mt-3">
                            <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                            <button type="submit" name="pdf" value="preview" class="btn btn-outline-info ms-2">Previsualizar PDF</button>
                        </div>
                    </form>

                    @if ($historial->isEmpty())
                        <p class="text-center text-muted">No hay ventas registradas para este producto en el rango de fechas seleccionado.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
