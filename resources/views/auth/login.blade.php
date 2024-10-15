@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('content')
<div class="contenedor__todo">
    <!-- Contenedor que agrupa el formulario y el cuadro del logo -->
    <div class="contenedor__central">
        <!-- Cuadro con el logo y el fondo -->
        <div class="caja__logo">
            <div class="caja__logo-container">
                <img src="{{ URL::asset('/assets/images/Logo_lanago.png') }}" alt="" height="150" class="logo logo-light animate__animated animate__fadeInDown">
            </div>
        </div>

        <!-- Contenedor para el formulario de login -->
        <div class="contenedor__login">
            <form method="POST" action="{{ route('login') }}" class="formulario__login">
                @csrf
                <h2>Iniciar Sesión</h2>

                <div class="mb-4">
                    <label class="form-label text-dark" for="email">Correo</label>
                    <input type="email" class="form-control bg-light text-dark border-0 shadow-sm @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" id="email"
                        placeholder="Ingrese su correo electrónico" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="float-end">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-muted">¿Olvidaste tu contraseña?</a>
                        @endif
                    </div>
                    <label class="form-label text-dark" for="userpassword">Contraseña</label>
                    <input type="password" class="form-control bg-light text-dark border-0 shadow-sm @error('password') is-invalid @enderror"
                        name="password" id="userpassword" placeholder="Ingrese su contraseña" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!--div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="auth-remember-check"
                        name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-dark" for="auth-remember-check">Recordar dispositivo</label>
                </!--div-->

                <div class="mt-4 text-end">
                    <button class="btn btn-primary w-100 shadow-sm" type="submit">Iniciar sesión</button>
                </div>

                <div class="mt-4 text-center">
                    <p class="mb-0 text-muted">¿No tienes una cuenta? Contacta con el administrador para más detalles.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 para mensajes de error -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}',
    });
</script>
@endif

<!-- CSS integrado en la vista -->
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f5f5f5;
    }

    .contenedor__todo {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: rgba(102, 153, 102, 0.7); /* Fondo verde transparente */
    }

    .contenedor__central {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 0px 20px 3px rgba(0, 0, 0, 0.1);
    }

    .caja__logo {
        background-image: url('{{ asset('assets/fondo3.png') }}');
        background-size: cover;
        background-position: center;
        width: 400px;
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    }

    .contenedor__login {
        width: 400px;
        padding: 30px;
    }

    .formulario__login h2 {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    .formulario__login input {
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
    }

    .formulario__login button {
        padding: 10px;
        background-color: #333;
        color: #fff;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .olvidoContraseña {
        margin-top: 10px;
        color: #007bff;
        text-decoration: none;
    }

    .form-check-input {
        margin-right: 5px;
    }
</style>
@endsection
