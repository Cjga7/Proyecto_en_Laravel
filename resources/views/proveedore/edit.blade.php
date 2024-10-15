@extends('layouts.master')

@section('title', 'Editar proveedor')

@push('css')
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Proveedor</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item active">Editar Proveedor</li>
        </ol>
        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('proveedores.update', ['proveedore' => $proveedore]) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="row g-3">

                    <!------Tipo de proveedor---->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_persona" class="form-label">Tipo de Proveedor</label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona">
                            <option value="natural" {{ $proveedore->persona->tipo_persona == 'natural' ? 'selected' : '' }}>Natural</option>
                            <option value="juridica" {{ $proveedore->persona->tipo_persona == 'juridica' ? 'selected' : '' }}>Jurídica</option>
                        </select>
                        @error('tipo_persona')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------Nombres y apellidos---->
                    <div class="col-md-6 mb-2">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre', $proveedore->persona->nombre) }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" class="form-control"
                            value="{{ old('primer_apellido', $proveedore->persona->primer_apellido) }}">
                        @error('primer_apellido')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control"
                            value="{{ old('segundo_apellido', $proveedore->persona->segundo_apellido) }}">
                        @error('segundo_apellido')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------Razón social---->
                    <div class="col-md-12 mb-2" id="box-razon-social" style="display: {{ $proveedore->persona->tipo_persona == 'juridica' ? 'block' : 'none' }};">
                        <label id="label-razon-social" for="razon_social" class="form-label">Razón Social</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control"
                            value="{{ old('razon_social', $proveedore->persona->razon_social) }}">
                        @error('razon_social')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------Dirección---->
                    <div class="col-md-12 mb-2">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control"
                            value="{{ old('direccion', $proveedore->persona->direccion) }}">
                        @error('direccion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------Documento---->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de Documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            @foreach ($documento as $item)
                                <option value="{{ $item->id }}"
                                    {{ $proveedore->persona->documento_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->tipo_documeto }}
                                </option>
                            @endforeach
                        </select>
                        @error('documento_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Número de Documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control"
                            value="{{ old('numero_documento', $proveedore->persona->numero_documento) }}">
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
        const razonSocialInput = document.getElementById('razon_social');

        function toggleRazonSocial() {
            if (tipoPersonaSelect.value === 'juridica') {
                razonSocialBox.style.display = 'block';
            } else {
                razonSocialBox.style.display = 'none';
                razonSocialInput.value = ''; // Limpia el campo de razón social
            }
        }

        toggleRazonSocial(); // Inicializa el estado

        tipoPersonaSelect.addEventListener('change', toggleRazonSocial);
    });
    </script>
@endsection

@push('js')
@endpush
