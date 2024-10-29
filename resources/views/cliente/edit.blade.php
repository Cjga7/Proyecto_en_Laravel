@extends('layouts.master')

@section('title', 'Editar Cliente')

@push('css')
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Cliente</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('clientes.index') }}">Clientes</a></li>
            <li class="breadcrumb-item active">Editar Cliente</li>
        </ol>
        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('clientes.update', ['cliente' => $cliente]) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="row g-3">

                    <!-- Tipo de Cliente -->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_persona" class="form-label">Tipo de Cliente</label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona">
                            <option value="natural" {{ $cliente->persona->tipo_persona == 'natural' ? 'selected' : '' }}>
                                Natural</option>
                            <option value="juridica" {{ $cliente->persona->tipo_persona == 'juridica' ? 'selected' : '' }}>
                                Jurídica</option>
                        </select>
                        @error('tipo_persona')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Nombres -->
                    <div class="col-md-6 mb-2">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre', $cliente->persona->nombre) }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Primer Apellido -->
                    <div class="col-md-6 mb-2">
                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" class="form-control"
                            value="{{ old('primer_apellido', $cliente->persona->primer_apellido) }}">
                        @error('primer_apellido')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Segundo Apellido -->
                    <div class="col-md-6 mb-2">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control"
                            value="{{ old('segundo_apellido', $cliente->persona->segundo_apellido) }}">
                        @error('segundo_apellido')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Razón Social (Solo para persona jurídica) -->
                    <div class="col-md-12 mb-2" id="box-razon-social"
                        style="display: {{ $cliente->persona->tipo_persona == 'juridica' ? 'block' : 'none' }};">
                        <label for="razon_social" class="form-label">Nombre de la Empresa</label>
                        <div id="razon-social-fields">
                            @foreach ($cliente->persona->razones_sociales ?? [] as $key => $razon_social)
                                <input type="text" name="razones_sociales[]" class="form-control mb-2"
                                    placeholder="Razón Social"
                                    value="{{ old('razones_sociales.' . $key, $razon_social) }}">
                                @if ($errors->has('razones_sociales.' . $key))
                                    <small
                                        class="text-danger">{{ '*' . $errors->first('razones_sociales.' . $key) }}</small>
                                @endif
                            @endforeach

                            <!-- Si no hay razones sociales, agrega un campo vacío por defecto -->
                            @if (empty($cliente->persona->razones_sociales))
                                <input type="text" name="razones_sociales[]" class="form-control mb-2"
                                    placeholder="Razón Social" value="{{ old('razones_sociales.0') }}">
                                @if ($errors->has('razones_sociales.0'))
                                    <small class="text-danger">{{ '*' . $errors->first('razones_sociales.0') }}</small>
                                @endif
                            @endif
                        </div>

                        <!-- Botón para agregar más razones sociales -->
                        <button type="button" class="btn btn-secondary mb-2" id="add-razon-social">Agregar otra razón
                            social</button>

                        <!-- Mostrar errores para todas las razones sociales -->
                        @foreach ($errors->get('razones_sociales.*') as $error)
                            <small class="text-danger">{{ '*' . $error[0] }}</small>
                        @endforeach
                    </div>



                    <!-- Dirección -->
                    <div class="col-md-12 mb-2">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control"
                            value="{{ old('direccion', $cliente->persona->direccion) }}">
                        @error('direccion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="col-md-6 mb-2">
                        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" id="correo_electronico" class="form-control"
                            value="{{ old('correo_electronico', $cliente->persona->correo_electronico) }}">
                        @error('correo_electronico')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6 mb-2">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control"
                            value="{{ old('telefono', $cliente->persona->telefono) }}">
                        @error('telefono')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de Documento</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            @foreach ($documento as $item)
                                <option value="{{ $item->id }}"
                                    {{ $cliente->persona->documento_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->tipo_documeto }}
                                </option>
                            @endforeach
                        </select>
                        @error('documento_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Número de Documento -->
                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Número de Documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control"
                            value="{{ old('numero_documento', $cliente->persona->numero_documento) }}">
                        @error('numero_documento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>


document.addEventListener('DOMContentLoaded', function() {
    const tipoPersonaSelect = document.getElementById('tipo_persona');
    const razonSocialBox = document.getElementById('box-razon-social');
    const addRazonSocialButton = document.getElementById('add-razon-social');
    const razonSocialFields = document.getElementById('razon-social-fields');

    function toggleRazonSocial() {
        if (tipoPersonaSelect.value === 'juridica') {
            razonSocialBox.style.display = 'block';
        } else {
            razonSocialBox.style.display = 'none';
            // Limpia los campos de razón social si el tipo es 'natural'
            while (razonSocialFields.firstChild) {
                razonSocialFields.removeChild(razonSocialFields.firstChild);
            }
            addRazonSocialField(); // Siempre deja al menos un campo vacío
        }
    }

    function addRazonSocialField() {
        // Crear un nuevo campo de texto para Razón Social
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'razones_sociales[]';
        input.classList.add('form-control', 'mb-2');
        input.placeholder = 'Razón Social';

        // Añadir el nuevo campo al contenedor
        razonSocialFields.appendChild(input);
    }

    // Evento para agregar más razones sociales dinámicamente
    addRazonSocialButton.addEventListener('click', function() {
        addRazonSocialField();
    });

    toggleRazonSocial(); // Inicializa el estado según el tipo de persona

    tipoPersonaSelect.addEventListener('change', toggleRazonSocial);
});


    </script>
@endsection

@push('js')
@endpush
