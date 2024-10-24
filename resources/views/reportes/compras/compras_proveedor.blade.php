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

    <!-- Formulario de filtro por mes y año -->
    <form action="{{ route('reportes.compras.proveedor') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="mes">Mes</label>
                    <select name="mes" id="mes" class="form-control">
                        <option value="">Todos</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('mes') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="anio">Año</label>
                    <input type="number" name="anio" id="anio" value="{{ request('anio', date('Y')) }}" class="form-control" placeholder="Año">
                </div>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                    <tr>
                                        <td>{{ $compra->proveedor }}</td>
                                        <td>{{ number_format($compra->total_compras, 2) }} Bs.</td>
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
