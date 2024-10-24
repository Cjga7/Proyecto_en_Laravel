@extends('layouts.master-without-nav')

@section('title', 'Restablecer Contraseña')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="text-primary">Restablecer Contraseña</h4>
                            <p class="text-muted">Ingrese su nueva contraseña para restablecerla.</p>
                        </div>

                        <!-- Formulario de restablecimiento -->
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Correo electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Nueva contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirmar nueva contraseña -->
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required>
                            </div>

                            <!-- Botón para restablecer contraseña -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100">Restablecer Contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Enlace para volver al login -->
                <div class="mt-4 text-center">
                    <p class="text-muted">¿Recuerdas tu contraseña? <a href="{{ route('login') }}" class="text-primary">Inicia sesión</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
