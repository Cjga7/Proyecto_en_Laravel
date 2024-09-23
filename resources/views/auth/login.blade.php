@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('content')
    <div class="account-pages my-5 pt-sm-5" style="background-image: url('{{ asset('assets/fondo3.png') }}'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="#" class="mb-5 d-block auth-logo">
                            <img src="{{ URL::asset('/assets/images/Logo_lanago.png') }}" alt="" height="250" class="logo logo-light">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg rounded-lg" style="background-color: rgba(0, 0, 0, 0.7); border: none;">

                        <div class="card-body p-5">
                            <div class="text-center mt-2">
                                <h5 class="text-white">¡Bienvenid@!</h5>
                                <p class="text-white-50">Inicia sesión con tu cuenta.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label text-white" for="email">Correo</label>
                                        <input type="email" class="form-control bg-dark text-white border-0 @error('email') is-invalid @enderror"
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
                                                <a href="{{ route('password.request') }}" class="text-white-50">¿Olvidaste tu contraseña?</a>
                                            @endif
                                        </div>
                                        <label class="form-label text-white" for="userpassword">Contraseña</label>
                                        <input type="password" class="form-control bg-dark text-white border-0 @error('password') is-invalid @enderror"
                                            name="password" id="userpassword" placeholder="Ingrese su contraseña" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="auth-remember-check"
                                            name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-white" for="auth-remember-check">Recordar dispositivo</label>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button class="btn btn-outline-light w-100 waves-effect waves-light" type="submit">Iniciar sesión</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0 text-white-50">¿No tienes una cuenta? <a href="{{ url('register') }}" class="fw-medium text-light"> Regístrate ahora </a></p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <p class="text-white">© <script>document.write(new Date().getFullYear())</script> Lanago. <i class="mdi mdi-heart text-danger"></i> La naturaleza en una gota</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
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
@endsection
