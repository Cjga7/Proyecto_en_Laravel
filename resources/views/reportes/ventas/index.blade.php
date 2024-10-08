@extends('layouts.master')

@section('title', 'Reportes de Ventas')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Reportes de Ventas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Reportes de Ventas</li>
        </ol>
        <div class="row mb-4">
            <!-- Ventas Totales -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-file-invoice-dollar"></i><span class="m-1">Ventas Totales</span>
                            </div>
                            <!--div class="col-4">
                                <p class="text-center fw-bold fs-4">{{ $ventasTotales }}</p>
                            </!--div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <!-- Asegúrate de tener la ruta 'ventas.totales' correctamente definida en tus rutas -->
                        <a class="small text-white stretched-link" href="{{ route('reportes.ventas.totales') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>


            <!-- Ventas por Producto -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-box"></i><span class="m-1">Ventas por Producto</span>
                            </div>
                            <!--div class="col-4">
                                <?php
                                    // Aquí puedes calcular las ventas por producto
                                    $ventasPorProducto = 50; // Reemplaza con la lógica de cálculo real
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $ventasPorProducto }}</p>
                            </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.ventas.producto') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Ventas por Cliente -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-user"></i><span class="m-1">Ventas por Cliente</span>
                            </div>
                            <!--div class="col-4">
                                <?php
                                    // Aquí puedes calcular las ventas por cliente
                                    $ventasPorCliente = 30; // Reemplaza con la lógica de cálculo real
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $ventasPorCliente }}</p>
                            </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.ventas.cliente') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Ventas por Usuario -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-user-tie"></i><span class="m-1">Ventas por Usuario</span>
                            </div>
                            <!--div class="col-4">
                                <?php
                                    // Aquí puedes calcular las ventas por usuario
                                    $ventasPorUsuario = 20; // Reemplaza con la lógica de cálculo real
                                ?>
                                <p class="text-center fw-bold fs-4">{{ $ventasPorUsuario }}</p>
                            </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.ventas.usuario') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
