@extends('layouts.master')

@section('title', 'Ventas por Producto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ventas por Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.ventas.index') }}">Reportes de Ventas</a></li>
        <li class="breadcrumb-item active">Ventas por Producto</li>
    </ol>

    <!-- Formulario para seleccionar mes y año -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Filtrar por Mes y Año</div>
                <div class="card-body">
                    <form action="{{ route('reportes.ventas.producto') }}" method="GET" class="form-inline">
                        <div class="form-group">
                            <label for="mes" class="mr-2">Mes:</label>
                            <select name="mes" id="mes" class="form-control mr-3">
                                <option value="">Todos</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ request('mes') == $month ? 'selected' : '' }}>
                                        {{ ucfirst(\Carbon\Carbon::create()->month($month)->locale('es')->translatedFormat('F')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="anio" class="mr-2">Año:</label>
                            <select name="anio" id="anio" class="form-control mr-3">
                                <option value="">Seleccione un año</option>
                                @foreach(range(now()->year, 2000) as $year)
                                    <option value="{{ $year }}" {{ request('anio') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Reporte de Ventas por Producto</div>
                <div class="card-body">
                    @if($ventas->isEmpty())
                        <p class="text-center">No hay ventas registradas para los productos seleccionados.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad Vendida</th>
                                        <th>Ingresos Totales (Bs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventas as $venta)
                                        <tr>
                                            <td>{{ $venta->producto }}</td>
                                            <td>{{ $venta->total_vendido }}</td>
                                            <td>{{ number_format($venta->total_ingresos, 2) }} Bs</td>
                                        </tr>
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
