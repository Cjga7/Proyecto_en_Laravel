@extends('layouts.master')

@section('title', 'Inventario de Productos')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Inventario de Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.productos.index') }}">Reportes de Productos</a></li>
        <li class="breadcrumb-item active">Inventario Actual</li>
    </ol>

    <!-- Barra de búsqueda y filtro -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('reportes.productos.inventario') }}" method="GET" class="d-flex">
                <input class="form-control me-2" type="search" name="search" placeholder="Buscar producto..." value="{{ request()->query('search') }}" aria-label="Buscar">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
        </div>
        <div class="col-lg-6">
            <form action="{{ route('reportes.productos.inventario') }}" method="GET">
                <div class="d-flex justify-content-end">
                    <select name="tipo" class="form-select me-2">
                        <option value="todos" {{ request()->query('tipo') == 'todos' ? 'selected' : '' }}>Todos</option>
                        <option value="terminado" {{ request()->query('tipo') == 'terminado' ? 'selected' : '' }}>Productos Terminados</option>
                        <option value="materia" {{ request()->query('tipo') == 'materia' ? 'selected' : '' }}>Materia Prima</option>
                    </select>
                    <button class="btn btn-outline-secondary" type="submit">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Inventario Actual</div>
                <div class="card-body">
                    @if ($productos->isEmpty())
                        <p>No hay productos disponibles en el inventario.</p>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categorías</th>
                                    <th>Registro Sanitario</th>
                                    <th>Presentación</th>
                                    <th>Stock</th>
                                    <th>Precio de Venta</th>
                                    <th>Fecha de Vencimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $item)
                                    <tr>
                                        <td>{{ $item->codigo }}</td>
                                    <td>{{ $item->nombre }}</td>
                                    <td>
                                        @foreach ($item->categorias as $category)
                                            <span class="badge bg-secondary m-1">{{ $category->caracteristica->nombre ?? 'No disponible' }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->registrosanitario->caracteristica->nombre ?? 'No disponible' }}</td>
                                    <td>{{ $item->presentacione->caracteristica->nombre ?? 'No disponible' }}</td>

                                        <td>{{ $item->stock }}</td>
                                        <td>{{ number_format($item->precio_venta, 2) }} Bs</td>
                                        <td>{{ $item->fecha_vencimiento ? \Carbon\Carbon::parse($item->fecha_vencimiento)->formatLocalized('%d %B %Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Controles de paginación -->
                        {{ $productos->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
