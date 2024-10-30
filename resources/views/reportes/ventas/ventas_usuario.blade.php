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

    <!-- Formulario de filtro por rango de fechas -->
    <form action="{{ route('reportes.ventas.usuario') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="fecha_fin">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control">
                </div>
            </div>

            <!-- Botones de Filtrar y Previsualizar PDF -->
            <div class="col-md-12 d-flex align-items-end mt-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>

                <!-- Enlace para previsualizar y luego imprimir el PDF -->
                <a href="{{ route('reportes.ventas.usuario', ['fecha_inicio' => request('fecha_inicio'), 'fecha_fin' => request('fecha_fin'), 'pdf' => 1]) }}"
                   class="btn btn-success ms-2" onclick="previsualizarPDF(event, this.href)">
                    <i class="fa fa-print"></i> Previsualizar PDF
                </a>
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

<!-- Script para previsualizar el PDF -->
<script>
    function previsualizarPDF(event, href) {
        event.preventDefault();
        window.open(href, '_blank');
    }
</script>

@endsection
