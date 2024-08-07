@extends('layouts.master')

@section('title', 'Clientes')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Custom DataTables Styles -->
    <link href="{{ URL::asset('/assets/css/custom-datatables.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}";
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            })
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Clientes</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Clientes</li>
        </ol>

        <div class="mb-4">
            <a href="{{ route('clientes.create') }}">
                <button type="button" class="btn btn-primary">Añadir un nuevo registro</button></a>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Clientes
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Direccion</th>
                                <th>Documento</th>
                                <th>Tipo de persona</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $item)
                                <tr>
                                    <td>{{ $item->persona->razon_social }}</td>
                                    <td>{{ $item->persona->direccion }}</td>
                                    <td>
                                        <p class="fw-normal mb-1">{{ $item->persona->documento->tipo_documeto }}</p>
                                        <p class="text-muted mb-0">{{ $item->persona->numero_documento }}</p>
                                    </td>
                                    <td>{{ $item->persona->tipo_persona }}</td>
                                    <td>
                                        @if ($item->persona->estado == 1)
                                            <span class="badge rounded-pill text-bg-success d-inline">Activo</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-danger d-inline">Eliminado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                            <form action="{{ route('clientes.edit', ['cliente' => $item]) }}" method="get">
                                                <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                                            </form>
                                            @if ($item->persona->estado == 1)
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $item->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $item->id }}">Restaurar</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal de confirmacion-->
                                <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmacion</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $item->persona->estado == 1 ? '¿Seguro que quieres eliminar este cliente?' : '¿Seguro que quieres restaurar este cliente?' }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <form action="{{ route('clientes.destroy', ['cliente'=> $item->persona->id]) }}" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- DataTables -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endpush
