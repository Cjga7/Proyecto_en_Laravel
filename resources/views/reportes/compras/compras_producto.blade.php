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
                        <div class="col-md-6">
                            <label for="mes" class="form-label">Mes:</label>
                            <select name="mes" id="mes" class="form-select">
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

                        <!-- Botones para previsualizar y descargar PDF -->
                        <div class="text-end mt-3">
                            <a href="{{ route('reportes.compras.producto', array_merge(request()->query(), ['pdf' => '1'])) }}" class="btn btn-secondary" target="_blank">
                                <i class="bi bi-eye"></i> Previsualizar PDF
                            </a>
                            <a href="{{ route('reportes.compras.producto', array_merge(request()->query(), ['pdf' => 'download'])) }}" class="btn btn-danger">
                                <i class="bi bi-download"></i> Descargar PDF
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
