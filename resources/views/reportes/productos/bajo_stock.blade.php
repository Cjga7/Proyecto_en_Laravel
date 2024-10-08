@extends('layouts.master')

@section('title', 'Productos con Bajo Stock')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos con Bajo Stock</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Bajo Stock</li>
    </ol>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Lista de Productos con Bajo Stock</div>
                <div class="mb-3 text-end">
                    <a href="{{ route('productos.ajustarStock') }}" class="btn btn-primary">Ajustar Stock</a>
                </div>
                <div class="card-body">
                    @if ($productos->isEmpty())
                        <p>No hay productos con bajo stock.</p>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
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
                                        <td>{{ $item->stock }}</td>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
