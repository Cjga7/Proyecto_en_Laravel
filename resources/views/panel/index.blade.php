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
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-people-group"></i><span class="m-1">Clientes</span>
                            </div>
                            <div class="col-4">
                                <?php
                                    use App\Models\Cliente;
                                    $clientes = count(Cliente::all());
                                ?>
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
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-store"></i><span class="m-1">Compras</span>
                            </div>
                            <div class="col-4">
                                <?php
                                    use App\Models\Compra;
                                    $compras = count(Compra::all());
                                ?>
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

            <!-- Ventas -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-store"></i><span class="m-1">Ventas</span>
                            </div>
                            <div class="col-4">
                                <?php
                                    use App\Models\Venta;
                                    $ventas = count(Venta::all());
                                ?>
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

            <!-- Gestion de Productos -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-secondary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-brands fa-shopify"></i><span class="m-1">Gestion de Productos</span>
                            </div>
                            <div class="col-4">
                                <?php
                                    use App\Models\Producto;
                                    $productos = count(Producto::all());
                                ?>
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

            <!-- Proveedores -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-user-group"></i><span class="m-1">Proveedores</span>
                            </div>
                            <div class="col-4">
                                <?php
                                    use App\Models\Proveedore;
                                    $proveedores = count(Proveedore::all());
                                ?>
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

            <!-- Usuarios -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-user"></i><span class="m-1">Usuarios</span>
                            </div>
                            <div class="col-4">
                                <?php
                                    use App\Models\User;
                                    $users = count(User::all());
                                ?>
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
        </div>

        <!-- Gráficos -->
        <div class="row">
            <!-- Área Chart -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-chart-line"></i>
                        Gráfico de Área
                    </div>
                    <div class="card-body">
                        <canvas id="areaChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>

            <!-- Barra Chart -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-chart-bar"></i>
                        Gráfico de Barras
                    </div>
                    <div class="card-body">
                        <canvas id="barChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-table"></i>
                Tabla de Datos
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí irán los datos -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/simple-datatables.min.js"></script>

    <script>
        // Gráfico de Área
        const ctxArea = document.getElementById('areaChart').getContext('2d');
        const areaChart = new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Ventas',
                    data: [30, 40, 35, 50, 60, 70],
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
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

        // Gráfico de Barras
        const ctxBar = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Ingresos',
                    data: [5000, 7000, 8000, 6000, 9000, 10000],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
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

        // Tabla de Datos
        document.addEventListener('DOMContentLoaded', () => {
            const dataTable = new DataTable('#dataTable');
        });
    </script>
@endpush
