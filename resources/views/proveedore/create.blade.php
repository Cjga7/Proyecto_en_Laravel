@extends('layouts.master')

@section('title', 'Crear proveedor')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <style>
        #box-razon-social {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Proveedor</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item active">Crear Proveedor</li>
        </ol>
        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('proveedores.store') }}" method="post">
                @csrf
                <div class="row g-3">

                    <!-- Tipo de persona -->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label">Tipo de proveedor:</label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona">
                            <option value="" selected disabled>Selecciona una opción</option>
                            <option value="natural" {{ old('tipo_persona') == 'natural' ? 'selected' : '' }}>Persona Natural</option>
                            <option value="juridica" {{ old('tipo_persona') == 'juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                        </select>
                        @error('tipo_persona')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Nombres y apellidos -->
                    <div id="box-nombres-apellidos" class="col-md-12 mb-2">
                        <label for="nombre" class="form-label">Nombres</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror

                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" class="form-control" value="{{ old('primer_apellido') }}">
                        @error('primer_apellido')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror

                        <label for="segundo_apellido" class="form-label">Segundo Apellido (Opcional)</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control" value="{{ old('segundo_apellido') }}">
                        @error('segundo_apellido')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Razon Social (solo para personas jurídicas) -->
                    <div id="box-razon-social" class="col-md-12 mb-2">
                        <label for="razon_social" class="form-label">Nombre de la Empresa</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}">
                        @error('razon_social')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-12 mb-2">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}">
                        @error('direccion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Documento -->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            <option value="" selected disabled>Selecciona una opción</option>
                            @foreach ($documentos as $item)
                                <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{ $item->tipo_documeto }}</option>
                            @endforeach
                        </select>
                        @error('documento_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Número de documento -->
                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Número de documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}">
                        <small id="documentoHelp" class="form-text text-muted"></small>
                        @error('numero_documento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="col-md-12 mb-2">
                        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" id="correo_electronico" class="form-control" value="{{ old('correo_electronico') }}">
                        @error('correo_electronico')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6 mb-2">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}">
                        @error('telefono')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Modal para Personas Jurídicas -->
                    <div class="modal fade" id="nitModal" tabindex="-1" aria-labelledby="nitModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nitModalLabel">Información Importante</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Para personas jurídicas, el tipo de documento debe ser NIT.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de guardar -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Función para mostrar u ocultar la razón social
        function toggleRazonSocial() {
            let tipoPersona = $('#tipo_persona').val();

            if (tipoPersona === 'juridica') {
                $('#box-razon-social').show();
                $('#nitModal').modal('show'); // Mostrar el modal informativo si es jurídica
            } else {
                $('#box-razon-social').hide();
            }
        }

        // Llamar la función cuando se carga la página (verificar el valor inicial)
        toggleRazonSocial();

        // Volver a llamar la función cuando el select cambie
        $('#tipo_persona').on('change', function() {
            toggleRazonSocial();
        });

        // Validaciones para NIT
        $('#documento_id').on('change', function() {
            let selectedDocType = $(this).find('option:selected').text();
            if (selectedDocType.toLowerCase() === 'nit') {
                $('#documentoHelp').text('El campo debe contener solo números.');
                $('#numero_documento').on('input', function() {
                    let value = $(this).val();
                    let isNumeric = /^\d*$/.test(value); // Validar solo números
                    if (!isNumeric) {
                        $(this).addClass('is-invalid');
                        $(this).removeClass('is-valid');
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).addClass('is-valid');
                    }
                });
            } else {
                $('#documentoHelp').text('');
                $('#numero_documento').off('input').removeClass('is-invalid is-valid');
            }
        });
    });
</script>
@endpush
