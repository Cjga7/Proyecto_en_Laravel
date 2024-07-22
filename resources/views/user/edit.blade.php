@extends('layouts.master')

@section('title','Editar usuario')

@push('css')

@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Usuarios</a></li>
        <li class="breadcrumb-item active">Editar Usuario</li>
    </ol>

    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form action="{{ route('users.update',['user'=>$user]) }}" method="post">
                @method('PATCH')
                @csrf
                <!---Nombre---->
                <div class="row mb-4">
                    <label for="name" class="col-md-auto col-form-label">Nombre:</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name',$user->name)}}">
                        <div class="col-sm-4">
                            <div class="form-text">
                                Escriba un solo nombre
                            </div>
                    </div>
                    </div>
                    <div class="col-sm-2">
                        @error('name')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <!---Email---->
                <div class="row mb-4">
                    <label for="email" class="col-md-auto col-form-label">Email:</label>
                    <div class="col-sm-4">
                        <input type="email" name="email" id="email" class="form-control" value="{{old('email',$user->email)}}">
                        <div class="col-sm-4">
                            <div class="form-text">
                                Direccion de correo electronico
                            </div>
                    </div>
                    </div>
                    <div class="col-ms-2">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <!---Password---->
                <div class="row mb-4">
                    <label for="password" class="col-md-auto col-form-label">Contraseña:</label>
                    <div class="col-sm-4">
                        <input type="password" name="password" id="password" class="form-control">
                        <div class="col-sm-4">
                            <div class="form-text">
                                Escriba una contrasena segura. Debe incluir numeros
                            </div>
                    </div>
                    </div>
                    <div class="col-ms-2">
                        @error('password')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                 <!---Confirm Password---->
                 <div class="row mb-4">
                    <label for="password_confirm" class="col-md-auto col-form-label">Confirmar Contraseña:</label>
                    <div class="col-sm-4">
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control">
                        <div class="col-sm-4">
                            <div class="form-text">
                               Vuelva a escribir su contraseña
                            </div>
                    </div>
                    </div>
                    <div class="col-ms-2">
                        @error('password_confirm')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                 <!---Roles---->
                 <div class="row mb-4">
                    <label for="role" class="col-md-auto col-form-label">Seleccionar Rol:</label>
                    <div class="col-sm-4">
                        <select name="role" id="role" class="form-select">
                            @foreach ($roles as $item)
                            @if (in_array($item->name,$user->roles()->pluck('name')->toArray()))
                            <option selected value="{{ $item->name }}"@selected(old('role') == $item->name)>{{ $item->name }}</option>
                            @else
                            <option value="{{ $item->name }}"@selected(old('role') == $item->name)>{{ $item->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="col-sm-4">
                            <div class="form-text">
                                Seleccione un rol para el usuario
                            </div>
                    </div>
                    </div>
                    <div class="col-ms-2">
                        @error('role')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>


                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>

            </form>
        </div>
    </div>


</div>
@endsection

@push('js')

@endpush
