@extends('layouts.master')

@section('title', 'Productos')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: "{{ session('success') }}",
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>

        <div class="mb-4">
            <a href="{{ route('productos.create') }}" class="btn btn-primary">Añadir un nuevo Producto</a>
        </div>

        <!-- Sección de Materias Primas -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i> Materias Primas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Registro sanitario</th>
                            <th>Presentación</th>
                            <th>Categorías</th>
                            <th>Tipo de Producto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $item)
                            @if ($item->tipoProducto && $item->tipoProducto->nombre == 'Materia Prima')
                                <tr>
                                    <td>{{ $item->codigo }}</td>
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->registrosanitario->caracteristica->nombre ?? 'No disponible' }}</td>
                                    <td>{{ $item->presentacione->caracteristica->nombre ?? 'No disponible' }}</td>
                                    <td>
                                        @foreach ($item->categorias as $category)
                                            <span class="badge bg-secondary m-1">{{ $category->caracteristica->nombre ?? 'No disponible' }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->tipoProducto->nombre }}</td>
                                    <td>
                                        <span class="badge {{ $item->estado ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->estado ? 'ACTIVO' : 'ELIMINADO' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                            <a href="{{ route('productos.edit', ['producto' => $item->id]) }}"
                                                class="btn btn-warning">Editar</a>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#verModal-{{ $item->id }}">Ver</button>
                                            @if ($item->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $item->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $item->id }}">Restaurar</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Ver -->
                                <div class="modal fade" id="verModal-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detalles del Producto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <label><strong>Descripción: </strong>{{ $item->descripcion }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Fecha de Vencimiento:
                                                        </strong>{{ $item->fecha_vencimiento ?: 'No tiene' }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Tipo de Producto:
                                                        </strong>{{ $item->tipoProducto->nombre }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Stock: </strong>{{ $item->stock }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Imagen</strong></label>
                                                    <div>
                                                        @if ($item->img_path)
                                                            <img src="{{ Storage::url('public/productos/' . $item->img_path) }}"
                                                                alt="{{ $item->nombre }}"
                                                                class="img-fluid img-thumbnail border border-4 rounded">
                                                        @else
                                                            <img src="" alt="{{ $item->nombre }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Confirmación Eliminar/Restaurar -->
                                <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $item->estado == 1 ? '¿Seguro que quieres eliminar este producto?' : '¿Seguro que quieres restaurar este producto?' }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <form action="{{ route('productos.destroy', ['producto' => $item->id]) }}"
                                                    method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn {{ $item->estado == 1 ? 'btn-danger' : 'btn-primary' }}">Confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


      <!-- Enlace para ajustar stock -->
      <div class="mb-3 text-end">
        <a href="{{ route('productos.ajustarStock') }}" class="btn btn-primary">Ajustar Stock</a>
    </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i> Productos Terminados
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Registro sanitario</th>
                            <th>Presentación</th>
                            <th>Categorías</th>
                            <th>Tipo de Producto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $item)
                            @if ($item->tipoProducto && $item->tipoProducto->nombre == 'Producto Terminado')
                                <tr>
                                    <td>{{ $item->codigo }}</td>
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->registrosanitario->caracteristica->nombre ?? 'No disponible' }}</td>
                                    <td>{{ $item->presentacione->caracteristica->nombre ?? 'No disponible' }}</td>
                                    <td>
                                        @foreach ($item->categorias as $category)
                                            <span class="badge bg-secondary m-1">{{ $category->caracteristica->nombre ?? 'No disponible' }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->tipoProducto->nombre }}</td>
                                    <td>
                                        <span class="badge {{ $item->estado ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->estado ? 'ACTIVO' : 'ELIMINADO' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                            <a href="{{ route('productos.edit', ['producto' => $item->id]) }}"
                                                class="btn btn-warning">Editar</a>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#verModal-{{ $item->id }}">Ver</button>
                                            @if ($item->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $item->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $item->id }}">Restaurar</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Ver -->
                                <div class="modal fade" id="verModal-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detalles del Producto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <label><strong>Descripción: </strong>{{ $item->descripcion }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Fecha de Vencimiento:
                                                        </strong>{{ $item->fecha_vencimiento ?: 'No tiene' }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Tipo de Producto:
                                                        </strong>{{ $item->tipoProducto->nombre }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Stock: </strong>{{ $item->stock }}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label><strong>Imagen</strong></label>
                                                    <div>
                                                        @if ($item->img_path)
                                                            <img src="{{ Storage::url('public/productos/' . $item->img_path) }}"
                                                                alt="{{ $item->nombre }}"
                                                                class="img-fluid img-thumbnail border border-4 rounded">
                                                        @else
                                                            <img src="" alt="{{ $item->nombre }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Confirmación Eliminar/Restaurar -->
                                <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $item->estado == 1 ? '¿Seguro que quieres eliminar este producto?' : '¿Seguro que quieres restaurar este producto?' }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <form action="{{ route('productos.destroy', ['producto' => $item->id]) }}"
                                                    method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn {{ $item->estado == 1 ? 'btn-danger' : 'btn-primary' }}">Confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gyb3qELFZ3hU4vZx7h1WwOa3A6A/5o7dCAWeW9BqxDjtR8tFbF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QD3B1QpI3C8RxIUI1R0zQzIwxKYvvR2/qX2hPaI9Um3jsptzFJxEe7/jG67M5JXl" crossorigin="anonymous"></script>
    <script>
        // Simple Datatables initialization
        const dataTable = new DataTable("#datatablesSimple");
    </script>
@endpush
