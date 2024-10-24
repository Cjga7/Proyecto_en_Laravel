@extends('layouts.master')

@section('title', 'Compras por Producto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Compras por Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.compras.index') }}">Reportes de Compras</a></li>
        <li class="breadcrumb-item active">Compras por Producto</li>
    </ol>

    <!-- Formulario para seleccionar mes y año -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Filtrar por Mes y Año</div>
                <div class="card-body">
                    <form action="{{ route('reportes.compras.producto') }}" method="GET" class="form-inline">
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
                <div class="card-header">Reporte de Compras por Producto</div>
                <div class="card-body">
                    @if($compras->isEmpty())
                        <p class="text-center">No hay compras registradas para los productos seleccionados.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad Comprada</th>
                                        <th>Gasto Total (Bs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($compras as $compra)
                                        <tr>
                                            <td>{{ $compra->producto }}</td>
                                            <td>{{ $compra->total_comprado }}</td>
                                            <td>{{ number_format($compra->total_gasto, 2) }} Bs</td>
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
