@extends('layouts.master')

@section('title', 'Historial de Ventas del Producto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Historial de Ventas del Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.productos.index') }}">Reportes de Productos</a></li>
        <li class="breadcrumb-item active">Historial de Ventas</li>
    </ol>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Historial de Ventas del Producto ID: {{ $productoId }}</div>
                <div class="card-body">
                    @if ($historial->isEmpty())
                        <p>No hay ventas registradas para este producto.</p>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Venta</th>
                                    <th>Fecha de Venta</th>
                                    <th>Cantidad Vendida</th>
                                    <th>Precio de Venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historial as $venta)
                                    <tr>
                                        <td>{{ $venta->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->formatLocalized('%d %B %Y %H:%M') }}</td>
                                        <td>{{ $venta->productos->firstWhere('id', $productoId)->pivot->cantidad ?? 'N/A' }}</td>
                                        <td>{{ number_format($venta->productos->firstWhere('id', $productoId)->pivot->precio_venta, 2) }} Bs</td>
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
@endsection
