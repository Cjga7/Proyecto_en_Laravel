@extends('layouts.master')

@section('title', 'Reportes de Productos')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Reportes de Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Reportes de Productos</li>
        </ol>
        <div class="row mb-4">
            <!-- Total de Productos -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-box"></i><span class="m-1">Total Productos</span>
                            </div>
                            <!---iv class="col-4">
                                    <?php
                                    use App\Models\Producto;
                                    $totalProductos = count(Producto::all());
                                    ?>
                                    <p class="text-center fw-bold fs-4">{{ $totalProductos }}</p>
                                </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.productos.inventario') }}">Ver
                            más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Productos Más Vendidos -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-dark mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-chart-line"></i><span class="m-1">Más Vendidos</span>
                            </div>
                            <div class="col-4">
                                <?php
                                // Ajusta esta consulta para obtener los productos más vendidos
                                $masVendidos = Producto::withCount('ventas')->orderBy('ventas_count', 'desc')->take(1)->count();
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $masVendidos }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-dark stretched-link" href="{{ route('reportes.productos.mas_vendidos') }}">Ver
                            más</a>
                        <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Productos con Bajo Stock -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-exclamation-triangle"></i><span class="m-1">Bajo Stock</span>
                            </div>
                            <div class="col-4">
                                <?php
                                // Ajusta esta consulta para contar productos con bajo stock
                                $bajoStock = Producto::where('stock', '<', 5)->count(); // Por ejemplo, stock bajo de 5
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $bajoStock }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.productos.bajo_stock') }}">Ver
                            más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Historial de Productos -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-history"></i><span class="m-1">Historial Productos</span>
                            </div>
                            <div class="col-4">
                                <?php
                                // Ajusta esta consulta para contar el historial de productos
                                $historialProductos = App\Models\Venta::count(); // O usa una consulta más específica
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $historialProductos }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link"
                            href="{{ route('reportes.productos.historial', ['id' => 'all']) }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
