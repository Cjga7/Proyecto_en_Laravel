@extends('layouts.master')

@section('title', 'Ventas Totales')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Ventas Totales por Día/Mes/Año</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.ventas.index') }}">Reportes de Ventas</a></li>
            <li class="breadcrumb-item active">Ventas Totales</li>
        </ol>
        <!-- Formulario de selección de mes y año -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Filtrar Ventas por Mes y Año</div>
                    <div class="card-body">
                        <form action="{{ route('reportes.ventas.totales') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="mes" class="form-label">Mes:</label>
                                    <select name="mes" id="mes" class="form-select">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('mes') == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="anio" class="form-label">Año:</label>
                                    <select name="anio" id="anio" class="form-select">
                                        @for ($i = date('Y'); $i >= 2000; $i--)
                                            <option value="{{ $i }}"
                                                {{ request('anio') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-12 d-flex align-items-end mt-2">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <!-- Enlace para previsualizar y luego imprimir el PDF -->
                                    <a href="{{ route('reportes.ventas.totales', ['mes' => request('mes'), 'anio' => request('anio'), 'pdf' => 1]) }}"
                                        class="btn btn-success ms-2" onclick="previsualizarPDF(event, this.href)">
                                        <i class="fa fa-print"></i> Previsualizar PDF
                                    </a>
                                    <a href="{{ route('reportes.ventas.totales', ['mes' => request('mes'), 'anio' => request('anio'), 'excel' => 1]) }}" class="btn btn-success ms-2">Descargar Excel</a>

                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar la tabla de ventas solo del mes seleccionado -->
        @if ($ventasDelMesSeleccionado->count() > 0)
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Reporte de Ventas para
                            {{ \Carbon\Carbon::create()->month($mesSeleccionado)->translatedFormat('F') }}
                            {{ $anio }}</div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Total Ventas (Bs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ventasDelMesSeleccionado as $venta)
                                        <tr>
                                            <td>{{ $venta->dia }}</td>
                                            <td>{{ number_format($venta->total, 2) }} Bs.</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mostrar el gráfico de barras con los totales de ventas por mes -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Gráfico de Ventas por Mes (Resaltando
                            {{ \Carbon\Carbon::create()->month($mesSeleccionado)->translatedFormat('F') }})</div>
                        <div class="card-body">
                            <canvas id="ventasTotalesPorMesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script para el gráfico -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('ventasTotalesPorMesChart').getContext('2d');

                // Etiquetas con los nombres de los meses
                const labels = @json($labels);

                // Totales de ventas por mes
                const datosVentas = @json($datosVentas);

                // Colores para resaltar el mes seleccionado
                const colores = @json($colores);

                // Crear el gráfico
                const ventasTotalesPorMesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Ventas (Bs.)',
                            data: datosVentas,
                            backgroundColor: colores,
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        @else
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="alert alert-info">
                        No se encontraron ventas para el mes seleccionado.
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Script para previsualizar y permitir imprimir el PDF -->
    <script>
        function previsualizarPDF(event, url) {
            event.preventDefault(); // Evita que el enlace descargue directamente el PDF
            const nuevaVentana = window.open(url, '_blank'); // Abre el PDF en una nueva ventana

            // Cuando el PDF esté completamente cargado en la nueva ventana
            nuevaVentana.onload = function() {
                if (confirm('¿Deseas imprimir el PDF?')) {
                    nuevaVentana.print(); // Si el usuario acepta, se inicia la impresión
                }
            };
        }
    </script>


@endsection
