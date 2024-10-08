@extends('layouts.master')

@section('title', 'Productos Más Vendidos')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos Más Vendidos</h1>

    <!-- Formulario de filtro por año y mes -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('reportes.productos.mas_vendidos') }}" method="GET" class="d-flex">
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
                <button class="btn btn-outline-primary" type="submit">Filtrar</button>
            </form>
        </div>
    </div>

    <!-- Resumen de productos más vendidos -->
    <div class="card mb-4">
        <div class="card-header">Resumen de Ventas</div>
        <div class="card-body">
            @if ($productos->isEmpty())
                <p>No hay productos vendidos en el mes y año seleccionados.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
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
                                <td>{{ $producto->total_vendido }}</td>
                                <td>{{ number_format($producto->ingresos, 2) }} Bs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Gráfica de barras de productos más vendidos -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Productos Más Vendidos</h4>
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
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
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
@endsection
