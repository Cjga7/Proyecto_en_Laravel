@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('content')
    <div class="account-pages my-6 pt-sm-6" style="background-image: url('{{ asset('assets/fondo3.png') }}'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="#" class="mb-6 d-block auth-logo">
                            <img src="{{ URL::asset('/assets/images/Logo_lanago.png') }}" alt="" height="250"
                                class="logo logo-dark">
                            <img src="{{ URL::asset('/assets/images/Logo_lanago.png') }}" alt="" height="250"
                                class="logo logo-light">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">

                    <div class="card" style="background-color: rgba(255, 255, 255, 0.8);">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Bienvenid@!</h5>
                                <p class="text-muted">Inicia Sesion con tu cuenta.</p>
                            </div>
                            <div class="p-2 mt-6">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="email">Correo</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" id="email"
                                            placeholder="Ingrese su correo electronico" required autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-muted">Olvidaste tu contraseña?</a>
                                            @endif
                                        </div>
                                        <label class="form-label" for="userpassword">Contraseña</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
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
                                        <label class="form-check-label" for="auth-remember-check">Recordar Dispositivo</label>
                                    </div>

                                    <div class="mt-6 text-end">
                                        <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Iniciar</button>
                                    </div>

                                    <!--div class="mt-4 text-center">
                                        <div class="signin-other-title" >
                                            <h5 class="font-size-14 mb-3 title">Sign in with</h5>
                                        </div>

                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <a href="javascript:void(0)"
                                                    class="social-list-item bg-primary text-white border-primary">
                                                    <i class="mdi mdi-facebook"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="javascript:void(0)"
                                                    class="social-list-item bg-info text-white border-info">
                                                    <i class="mdi mdi-twitter"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="javascript:void(0)"
                                                    class="social-list-item bg-danger text-white border-danger">
                                                    <i class="mdi mdi-google"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </!--div-->

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Don't have an account? <a href="{{ url('register') }}"
                                                class="fw-medium text-primary"> Signup now </a> </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-7 text-center">
                        <p class="text-primary">© <script>
                                document.write(new Date().getFullYear())
                            </script> Lanago <i class="mdi mdi-heart text-danger"></i> La naturaleza en una gota</p>
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
