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

    <!-- Formulario para seleccionar rango de fechas -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Filtrar por Rango de Fechas</div>
                <div class="card-body">
                    <form action="{{ route('reportes.compras.producto') }}" method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
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

                        <!-- Botones para previsualizar y descargar PDF -->
                        <div class="text-end mt-3">
                            <a href="{{ route('reportes.compras.producto', array_merge(request()->query(), ['pdf' => '1'])) }}" class="btn btn-secondary" target="_blank">
                                <i class="bi bi-eye"></i> Previsualizar PDF
                            </a>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
