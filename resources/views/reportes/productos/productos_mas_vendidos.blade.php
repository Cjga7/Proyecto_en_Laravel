@extends('layouts.master')

@section('title', 'Productos Más Vendidos')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos Más Vendidos</h1>

    <!-- Barra de navegación -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.productos.index') }}">Reportes de Productos</a></li>
        <li class="breadcrumb-item active">Productos Más Vendidos</li>
    </ol>

    <!-- Formulario de filtro por año y mes -->
    <div class="row mb-4 justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('reportes.productos.mas_vendidos') }}" method="GET" class="d-flex justify-content-center">
                <select name="anio" class="form-select me-2">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $i == $anio ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <select name="mes" class="form-select me-2">
                    @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $nombreMes)
                        <option value="{{ $index + 1 }}" {{ $index + 1 == $mes ? 'selected' : '' }}>{{ $nombreMes }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-funnel-fill"></i> Filtrar
                </button>
            </form>
        </div>
    </div>

    <!-- Botones de previsualización y descarga de PDF -->
    <div class="row mb-4 justify-content-center">
        <div class="col-lg-8 d-flex justify-content-end">
            <a href="{{ route('reportes.productos.mas_vendidos', array_merge(request()->query(), ['pdf' => 'preview'])) }}" class="btn btn-info me-2">
                <i class="bi bi-eye"></i> Previsualizar PDF
            </a>
            <a href="{{ route('reportes.productos.mas_vendidos', array_merge(request()->query(), ['pdf' => 'download'])) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
            </a>
        </div>
    </div>

    <!-- Resumen de productos más vendidos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-graph-up"></i> Resumen de Ventas
        </div>
        <div class="card-body">
            @if ($productos->isEmpty())
                <p class="text-center text-muted">No hay productos vendidos en el mes y año seleccionados.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Nombre</th>
                                <th>Total Vendido</th>
                                <th>Ingresos Generados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr>
                                    <td>{{ $producto->nombre }}</td>
                                    <td class="fw-bold">{{ $producto->total_vendido }}</td>
                                    <td>{{ number_format($producto->ingresos, 2) }} Bs</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Gráfica de barras de productos más vendidos -->
    <div class="row mb-4">
        <div class="col-xl-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Productos Más Vendidos</h4>
                    <canvas id="bar" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para generar la gráfica con Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('bar').getContext('2d');
    const nombresProductos = @json($nombresProductos);
    const cantidadesVendidas = @json($cantidadesVendidas);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: nombresProductos,
            datasets: [{
                label: 'Cantidad Vendida',
                data: cantidadesVendidas,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(75, 192, 192, 0.8)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return ' Cantidad Vendida: ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad Vendida'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Producto'
                    }
                }
            }
        }
    });
</script>
@endsection
