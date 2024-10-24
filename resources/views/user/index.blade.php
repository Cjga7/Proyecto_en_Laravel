@extends('layouts.master')

@section('title', 'Usuarios')

@push('css')
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
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
        <h1 class="mt-4 text-center">Usuarios</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
        </ol>

        <div class="mb-4">
            <a href="{{ route('users.create') }}">
                <button type="button" class="btn btn-primary">Añadir un nuevo usuario</button>
            </a>
        </div>

        <div class="row">
            @foreach ($users as $user)
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="dropdown float-end">
                                <a class="text-body dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                    <i class="uil uil-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Editar</a>
                                    <button class="dropdown-item text-danger" onclick="confirmDeletion('{{ $user->id }}')">Eliminar</button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="mb-4">
                                <img src="{{ URL::asset('/assets/images/users/logoadmin.jpg') }}" alt="" class="avatar-lg rounded-circle img-thumbnail">
                            </div>
                            <h5 class="font-size-16 mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-2">{{ $user->getRoleNames()->first() }}</p>
                            <h5 class="font-size-16 mb-1">{{ $user->email }}</h5>
                        </div>

                        <!---div class="btn-group" role="group">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-warning text-truncate">
                                <i class="uil uil-edit"></i> Editar
                            </a>
                            <button class="btn btn-outline-danger text-truncate" onclick="confirmDeletion('{{ $user->id }}')">
                                <i class="uil uil-trash"></i> Eliminar
                            </button>
                        </!---div---->
                    </div>
                </div>

                <!-- Modal de confirmacion -->
                <div class="modal fade" id="confirmModal-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ¿Seguro que quieres eliminar el usuario?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script>
        function confirmDeletion(userId) {
            $('#confirmModal-' + userId).modal('show');
        }
    </script>
@endpush
