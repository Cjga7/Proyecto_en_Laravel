@extends('layouts.master')

@section('title', 'Ventas Totales')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Ventas Totales por Rango de Fechas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.ventas.index') }}">Reportes de Ventas</a></li>
            <li class="breadcrumb-item active">Ventas Totales</li>
        </ol>
        <!-- Formulario de selección de rango de fechas -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Filtrar Ventas por Rango de Fechas</div>
                    <div class="card-body">
                        <form action="{{ route('reportes.ventas.totales') }}" method="GET">
                            <div class="row">
                                <!-- Fecha de Inicio -->
                                <div class="col-md-6">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                                </div>
                                <!-- Fecha de Fin -->
                                <div class="col-md-6">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                                </div>
                                <!-- Botones de acción -->
                                <div class="col-md-12 d-flex align-items-end mt-3">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <!-- Enlace para previsualizar y luego imprimir el PDF -->
                                    <a href="{{ route('reportes.ventas.totales', ['fecha_inicio' => request('fecha_inicio'), 'fecha_fin' => request('fecha_fin'), 'pdf' => 1]) }}"
                                       class="btn btn-success ms-2" onclick="previsualizarPDF(event, this.href)">
                                        <i class="fa fa-print"></i> Previsualizar PDF
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar tabla y gráfico de ventas filtradas -->
        @if ($ventas->count() > 0)
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Reporte de Ventas</div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Total Ventas (Bs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ventas as $venta)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($venta->total, 2) }} Bs.</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de barras de ventas filtradas -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Gráfico de Ventas</div>
                        <div class="card-body">
                            <canvas id="ventasTotalesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script para el gráfico -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('ventasTotalesChart').getContext('2d');
                const labels = @json($labels);
                const datosVentas = @json($datosVentas);
                const colores = @json($colores);

                const ventasTotalesChart = new Chart(ctx, {
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
                        No se encontraron ventas para el período seleccionado.
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Script para previsualizar PDF -->
    <script>
        function previsualizarPDF(event, url) {
            event.preventDefault();
            const nuevaVentana = window.open(url, '_blank');
            nuevaVentana.onload = function() {
                if (confirm('¿Deseas imprimir el PDF?')) {
                    nuevaVentana.print();
                }
            };
        }
    </script>
@endsection
