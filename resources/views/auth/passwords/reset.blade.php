@extends('layouts.master-without-nav')

@section('title', 'Restablecer Contraseña')

@section('content')
    <div class="container">
        <h1>Restablecer Contraseña</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email">Correo</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required>
            </div>
            <div>
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label for="password-confirm">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit">Restablecer Contraseña</button>
        </form>
    </div>
@endsection
