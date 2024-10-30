@extends('layouts.master')

@section('title', 'Panel')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let message = "{{ session('success') }}";
                Swal.fire(message);
            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4">Panel</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Panel</li>
        </ol>
        <div class="row">
            <!-- Clientes -->
            @can('ver-cliente')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-people-group"></i><span class="m-1">Clientes</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $clientes = \App\Models\Cliente::count();
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{ $clientes }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('clientes.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Categorías -->
            <!--div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <i class="fa-solid fa-tag"></i><span class="m-1">Categorías</span>
                                            </div>
                                            <div class="col-4">
                                                <?php
                                                use App\Models\Categoria;
                                                $categorias = count(Categoria::all());
                                                ?>
                                                <p class="text-center fw-bold fs-4">{{ $categorias }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{ route('categorias.index') }}">Ver más</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div-->

            <!-- Compras -->
            @can('ver-compra')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-store"></i><span class="m-1">Compras</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $compras = \App\Models\Compra::count();
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{ $compras }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('compras.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!-- Ventas -->
            @can('ver-venta')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-store"></i><span class="m-1">Ventas</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $ventas = \App\Models\Venta::count();
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{ $ventas }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('ventas.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- registro sanitario -->
            <!---div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <i class="fa-solid fa-bullhorn"></i><span class="m-1">Registro sanitario</span>
                                            </div>
                                            <div class="col-4">
                                                <?php
                                                use App\Models\Registrosanitario;
                                                $registrosanitarios = count(Registrosanitario::all());
                                                ?>
                                                <p class="text-center fw-bold fs-4">{{ $registrosanitarios }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{ route('registrosanitarios.index') }}">Ver más</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div-->

            <!-- Presentaciones -->
            <!---div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <i class="fa-solid fa-box-archive"></i><span class="m-1">Presentaciones</span>
                                            </div>
                                            <div class="col-4">
                                                <?php
                                                use App\Models\Presentacione;
                                                $presentaciones = count(Presentacione::all());
                                                ?>
                                                <p class="text-center fw-bold fs-4">{{ $presentaciones }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{ route('presentaciones.index') }}">Ver más</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div--->

            <!-- Gestión de Productos -->
            @can('ver-producto')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-secondary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-brands fa-shopify"></i><span class="m-1">Gestión de Productos</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $productos = \App\Models\Producto::count();
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{ $productos }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Proveedores -->
            @can('ver-proveedor')
                <!-- Asegúrate de que este sea el nombre correcto -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-user-group"></i><span class="m-1">Proveedores</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $proveedores = \App\Models\Proveedore::count(); // Cambiar a count() para eficiencia
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{ $proveedores }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('proveedores.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!-- Usuarios -->
            @can('ver-user')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <i class="fa-solid fa-user"></i><span class="m-1">Usuarios</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $users = \App\Models\User::count(); // Cambia a count() para mejorar la eficiencia
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{ $users }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('users.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan

          <!-- Gráficos -->
<div class="row">

    <!-- Barra Chart -->
    <div class="col-xl-12">
        <div class="card mb-4 shadow-lg">
            <div class="card-header text-center bg-primary text-white">
                <i class="fa-solid fa-chart-bar"></i>
                <strong>Ventas Mensuales por Producto</strong>
            </div>
            <div class="card-body p-4">
                <canvas id="barChart" style="width: 100%; height: 70vh;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json(array_values($meses));

    const datasets = [
        @foreach($productosData as $producto => $ventas)
        {
            label: "{{ $producto }}",
            data: [
                @foreach($meses as $mes)
                    {{ $ventas[$mes] ?? 0 }},
                @endforeach
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            hoverBackgroundColor: 'rgba(54, 162, 235, 0.8)',
        },
        @endforeach
    ];

    const ctxBar = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 10,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#333',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    },
                    ticks: {
                        color: '#333',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return value.toLocaleString(); // Formato de miles
                        }
                    }
                }
            }
        }
    });
</script>





        </div>

    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.min.js"></script>

        <script>
            const ctxBar = document.getElementById('barChart').getContext('2d');

            const labels = @json(array_values($meses));
            const datasets = [
                @foreach($productosData as $producto => $ventas)
                {
                    label: "{{ $producto }}",
                    data: [
                        @foreach($meses as $mes)
                            {{ $ventas[$mes] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                @endforeach
            ];

            const barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>


    @endpush
