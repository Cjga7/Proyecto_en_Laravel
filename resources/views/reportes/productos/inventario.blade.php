@extends('layouts.master')

@section('title', 'Inventario de Productos')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Todos los Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes.productos.index') }}">Reportes de Productos</a></li>
        <li class="breadcrumb-item active">Productos Actuales</li>
    </ol>

    <!-- Barra de búsqueda, filtro y botón de descarga PDF -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-2">
            <form action="{{ route('reportes.productos.inventario') }}" method="GET" class="d-flex">
                <input class="form-control me-2" type="search" name="search" placeholder="Buscar producto..." value="{{ request()->query('search') }}" aria-label="Buscar">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </form>
        </div>
        <div class="col-lg-4 mb-2">
            <form action="{{ route('reportes.productos.inventario') }}" method="GET">
                <div class="d-flex justify-content-center">
                    <select name="tipo" class="form-select me-2">
                        <option value="todos" {{ request()->query('tipo') == 'todos' ? 'selected' : '' }}>Todos</option>
                        <option value="terminado" {{ request()->query('tipo') == 'terminado' ? 'selected' : '' }}>Productos Terminados</option>
                        <option value="materia" {{ request()->query('tipo') == 'materia' ? 'selected' : '' }}>Materia Prima</option>
                    </select>
                    <button class="btn btn-secondary" type="submit">
                        <i class="bi bi-funnel-fill"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
        <div class="col-lg-4 mb-2 d-flex justify-content-end">
            <a href="{{ route('reportes.productos.inventario', array_merge(request()->query(), ['preview' => 'pdf'])) }}" class="btn btn-info me-2">
                <i class="bi bi-eye"></i> Previsualizar PDF
            </a>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-box-seam"></i> Inventario Actual
                </div>
                <div class="card-body">
                    @if ($productos->isEmpty())
                        <p class="text-center text-muted">No hay productos disponibles en el inventario.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-primary">
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
                                                    <span class="badge bg-info text-dark">{{ $category->caracteristica->nombre ?? 'No disponible' }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->registrosanitario->caracteristica->nombre ?? 'No disponible' }}</td>
                                            <td>{{ $item->presentacione->caracteristica->nombre ?? 'No disponible' }}</td>
                                            <td class="fw-bold">{{ $item->stock }}</td>
                                            <td>{{ number_format($item->precio_venta, 2) }} Bs</td>
                                            <td>{{ $item->fecha_vencimiento ? \Carbon\Carbon::parse($item->fecha_vencimiento)->formatLocalized('%d %B %Y') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Controles de paginación -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $productos->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
