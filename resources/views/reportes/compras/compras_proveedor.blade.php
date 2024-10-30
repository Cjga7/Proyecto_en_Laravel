@extends('layouts.master')

@section('title', 'Compras por Proveedor')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Compras por Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.compras.index') }}">Reportes de Compras</a></li>
        <li class="breadcrumb-item active">Compras por Proveedor</li>
    </ol>

    <!-- Formulario de filtro por rango de fechas -->
    <form action="{{ route('reportes.compras.proveedor') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_fin">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('reportes.compras.proveedor', array_merge(request()->query(), ['pdf' => '1'])) }}" class="btn btn-secondary" target="_blank">
                    <i class="bi bi-eye"></i> Previsualizar PDF
                </a>

            </div>
        </div>
    </form>

    <!-- Tabla de compras por proveedor -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Reporte de Compras por Proveedor</div>
                <div class="card-body">
                    @if($compras->isEmpty())
                        <p>No se encontraron compras para los criterios seleccionados.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Total Compras (Bs.)</th>
                                    <th>Total Productos Comprados</th>
                                    <th>Productos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                    <tr>
                                        <td>{{ $compra->proveedor }}</td>
                                        <td>{{ number_format($compra->total_compras, 2) }} Bs.</td>
                                        <td>{{ $compra->total_productos_comprados }}</td>
                                        <td>{{ $compra->productos }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
