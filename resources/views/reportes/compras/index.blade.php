@extends('layouts.master')

@section('title', 'Reportes de Compras')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Reportes de Compras</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Reportes de Compras</li>
        </ol>
        <div class="row mb-4">
            <!-- Compras Totales -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-file-invoice-dollar"></i><span class="m-1">Compras Totales</span>
                            </div>
                            <!--div class="col-4">
                                <p class="text-center fw-bold fs-4">{{ $comprasTotales }}</p>
                            </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.compras.totales') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Compras por Producto -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-box"></i><span class="m-1">Compras por Producto</span>
                            </div>
                            <!--div class="col-4">
                                <p class="text-center fw-bold fs-4">{{ $comprasPorProducto }}</p>
                            </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.compras.producto') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Compras por Proveedor -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-truck"></i><span class="m-1">Compras por Proveedor</span>
                            </div>
                            <!--div class="col-4">
                                <p class="text-center fw-bold fs-4">{{ $comprasPorProveedor }}</p>
                            </div-->
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('reportes.compras.proveedor') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
