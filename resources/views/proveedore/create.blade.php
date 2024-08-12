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

                    <!------tipo de persona---->
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label">Tipo de Proveedor:</label>
                        <select class="form-select" name="tipo_persona" id="tipo_persona">
                            <option value="" selected disabled>Selecciona una opción</option>
                            <option value="natural" {{ old('tipo_persona') == 'natural' ? 'selected' : '' }}>Persona Natural</option>
                            <option value="juridica" {{ old('tipo_persona') == 'juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                        </select>
                        @error('tipo_persona')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------Razon social---->
                    <div class="col-md-12 mb-2" id="box-razon-social">
                        <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos</label>
                        <label id="label-juridica" for="razon_social" class="form-label">Nombre de la empresa</label>

                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{ old('razon_social') }}">

                        @error('razon_social')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------direccion---->
                    <div class="col-md-12 mb-2">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control">
                        @error('direccion')
                        <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!------documento---->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            <option value="" selected disabled>Selecciona una opción</option>
                            @foreach ($documentos as $item )
                            <option value="{{ $item->id }}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{ $item->tipo_documeto }}</option>
                            @endforeach
                        </select>
                        @error('documento_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Número de documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}">
                        <small id="documentoHelp" class="form-text text-muted"></small>
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

        <!-- Modal -->
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

    </div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
        $('#tipo_persona').on('change', function() {
            let selectValue = $(this).val();
            if(selectValue == 'natural'){
                $('#label-juridica').hide();
                $('#label-natural').show();
            } else {
                $('#label-natural').hide();
                $('#label-juridica').show();
                $('#nitModal').modal('show');
            }

            $('#box-razon-social').show();
        });

        $('#documento_id').on('change', function() {
            let selectedDocType = $(this).find('option:selected').text();
            if (selectedDocType.toLowerCase() === 'nit') {
                $('#documentoHelp').text('El campo debe contener solo números.');
                $('#numero_documento').on('input', function() {
                    let value = $(this).val();
                    let isNumeric = /^\d*$/.test(value); // Permite solo números
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
