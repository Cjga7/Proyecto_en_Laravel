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

                        <!-- Formulario para seleccionar mes y año -->
                        <form action="{{ route('reportes.productos.historial', $productoId) }}" method="GET">
                            <!-- Campos de filtro mes y año -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="mes" class="form-label">Mes:</label>
                                    <select name="mes" id="mes" class="form-select">
                                        <option value="">Todos</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ request('mes') == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="anio" class="form-label">Año:</label>
                                    <select name="anio" id="anio" class="form-select">
                                        @for ($i = date('Y'); $i >= 2000; $i--)
                                            <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Botón de filtro y generación de PDF -->
                            <div class="col-md-12 d-flex align-items-end mt-3">
                                <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                                <button type="submit" name="pdf" value="1" class="btn btn-outline-danger ms-2">Ver en PDF</button>
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
