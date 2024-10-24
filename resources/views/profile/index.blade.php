@extends('layouts.master')

@section('title', 'Perfil')

@push('css')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="mb-4">Configurar Perfil</h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Configuración de Perfil</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('profile.update', ['profile' => $user]) }}" method="POST">
                @method('PATCH')
                @csrf

                <!-- Nombre -->
                <div class="mb-3 row">
                    <label for="name" class="col-md-3 col-form-label">Nombres</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" placeholder="Ingrese su nombre completo">
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3 row">
                    <label for="email" class="col-md-3 col-form-label">Email</label>
                    <div class="col-md-9">
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="Ingrese su correo electrónico">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3 row">
                    <label for="password" class="col-md-3 col-form-label">Contraseña</label>
                    <div class="col-md-9">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese una nueva contraseña">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Scripts adicionales aquí -->
@endpush
