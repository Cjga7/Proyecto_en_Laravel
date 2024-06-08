@extends('template')

@section('title', 'Clientes')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
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
                <table id="datatablesSimple" class="table table-striped">
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
                                <td>
                                    {{ $item->persona->razon_social }}
                                </td>
                                <td>
                                    {{ $item->persona->direccion }}
                                </td>
                                <td>
                                    <p class="fw-normal mb-1">{{ $item->persona->documento->tipo_documeto }}</p>
                                    <p class="text-muted mb-0">{{ $item->persona->numero_documento }}</p>
                                </td>
                                <td>
                                    {{ $item->persona->tipo_persona }}
                                </td>
                                <td>
                                    @if ($item->persona->estado == 1)
                                        <span class="badge rounded-pill text-bg-success d-inline">Activo</span>
                                    @else
                                    <span class="badge rounded-pill text-bg-danger d-inlinee">Eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        <form action="{{ route('clientes.edit', ['cliente' => $item]) }}" method="get">
                                            <button type="submit" class="btn btn-warning">Editar</button>
                                        </form>

                                        @if ($item->persona->estado == 1)
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                        @else

                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
