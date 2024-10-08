@extends('layouts.master')

@section('title', 'Ventas por Usuario')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ventas por Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.ventas.index') }}">Reportes de Ventas</a></li>
        <li class="breadcrumb-item active">Ventas por Usuario</li>
    </ol>

    <!-- Formulario de filtro por mes y año -->
    <form action="{{ route('reportes.ventas.usuario') }}" method="GET" class="mb-4">
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

    <!-- Tabla de ventas por usuario -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Reporte de Ventas por Usuario</div>
                <div class="card-body">
                    @if($ventas->isEmpty())
                        <p>No se encontraron ventas para los criterios seleccionados.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Total Productos Vendidos</th>
                                    <th>Total Ingresos (Bs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventas as $venta)
                                    <tr>
                                        <td>{{ $venta->usuario }}</td>
                                        <td>{{ $venta->total_productos_vendidos }}</td>
                                        <td>{{ number_format($venta->total_ingresos, 2) }} Bs.</td>
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
