@extends('layouts.master')

@section('title', 'Productos con Bajo Stock')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos con Bajo Stock</h1>

    <!-- Barra de navegación -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Bajo Stock</li>
    </ol>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-exclamation-triangle-fill"></i> Lista de Productos con Bajo Stock
                </div>

                <!-- Botones de Ajustar Stock, Descargar PDF y Previsualizar PDF -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('productos.ajustarStock') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajustar Stock
                    </a>
                    <div>
                        <a href="{{ route('reportes.productos.bajo_stock', ['download' => 'pdf']) }}" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
                        </a>
                        <a href="{{ route('reportes.productos.bajo_stock', ['view' => 'pdf']) }}" class="btn btn-secondary">
                            <i class="bi bi-eye"></i> Previsualizar PDF
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($productos->isEmpty())
                        <p class="text-center text-muted">No hay productos con bajo stock.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-danger">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Stock Actual</th>
                                        <th>Registro Sanitario</th>
                                        <th>Presentación</th>
                                        <th>Categorías</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productos as $item)
                                        <tr>
                                            <td>{{ $item->codigo }}</td>
                                            <td>{{ $item->nombre }}</td>
                                            <td class="fw-bold text-danger">{{ $item->stock }}</td>
                                            <td>{{ $item->registrosanitario->caracteristica->nombre ?? 'No disponible' }}</td>
                                            <td>{{ $item->presentacione->caracteristica->nombre ?? 'No disponible' }}</td>
                                            <td>
                                                @foreach ($item->categorias as $category)
                                                    <span class="badge bg-secondary m-1">{{ $category->caracteristica->nombre ?? 'No disponible' }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
