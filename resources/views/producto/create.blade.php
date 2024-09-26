@extends('layouts.master')

@section('title', 'Crear Producto')

@push('css')
    <style>
        #descripcion {
            resize: none;
        }
    </style>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Producto</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">Crear Producto</li>
        </ol>
        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    <!-- Tipo de Producto -->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_producto_id" class="form-label">Tipo de Producto:</label>
                        <select data-size="4" title="Seleccione un tipo de producto" data-live-search="true"
                            name="tipo_producto_id" id="tipo_producto_id" class="form-control selectpicker show-tick">
                            @foreach ($tiposProductos as $item)
                                <option
                                    value="{{ $item->id }}"{{ old('tipo_producto_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('tipo_producto_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Código -->
                    <div class="col-md-6 mb-2">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control"
                            value="{{ old('codigo') }}">
                        @error('codigo')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6 mb-2">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre') }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-12 mb-2">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Fecha de Vencimiento -->
                    <div class="col-md-6 mb-2">
                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento:</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"
                            value="{{ old('fecha_vencimiento') }}">
                        @error('fecha_vencimiento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div class="col-md-6 mb-2">
                        <label for="img_path" class="form-label">Imagen:</label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                        @error('img_path')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Registro Sanitario (oculto por defecto) -->
                    <div class="col-md-6 mb-2" id="registrosanitario_container" style="display: none;">
                        <label for="registrosanitario_id" class="form-label">Registro Sanitario:</label>
                        <select data-size="4" title="Seleccione un registro sanitario" data-live-search="true"
                            name="registrosanitario_id" id="registrosanitario_id"
                            class="form-control selectpicker show-tick">
                            @foreach ($registrosanitarios as $item)
                                <option
                                    value="{{ $item->id }}"{{ old('registrosanitario_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('registrosanitario_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Presentación -->
                    <div class="col-md-6 mb-2">
                        <label for="presentacione_id" class="form-label">Presentación:</label>
                        <select data-size="4" title="Seleccione una presentación" data-live-search="true"
                            name="presentacione_id" id="presentacione_id" class="form-control selectpicker show-tick">
                            @foreach ($presentaciones as $item)
                                <option
                                    value="{{ $item->id }}"{{ old('presentacione_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('presentacione_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Categorías -->
                    <div class="col-md-6 mb-2">
                        <label for="categorias" class="form-label">Categoría:</label>
                        <select data-size="4" title="Seleccione una categoría" data-live-search="true" name="categorias[]"
                            id="categorias" class="form-control selectpicker show-tick" multiple>
                            @foreach ($categorias as $item)
                                <option
                                    value="{{ $item->id }}"{{ in_array($item->id, old('categorias', [])) ? 'selected' : '' }}>
                                    {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categorias')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>


                    <!-- Precio de Venta (oculto por defecto) -->
                    <div class="col-md-6 mb-2" id="precio_venta_container" style="display: none;">
                        <label for="precio_venta" class="form-label">Precio de Venta:</label>
                        <input type="number" name="precio_venta" id="precio_venta" class="form-control"
                            value="{{ old('precio_venta') }}" step="0.01" min="0">
                        @error('precio_venta')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tipo_producto_id').change(function() {
                const tipoProductoId = $(this).val();

                // Mostrar/ocultar el registro sanitario según el tipo de producto
                if (tipoProductoId == 1) { // Cambia '1' por el ID del producto terminado
                    $('#registrosanitario_container').show();
                    $('#precio_venta_container').show();
                } else {
                    $('#registrosanitario_container').hide();
                    $('#precio_venta_container').hide();
                }
            });

            // Forzar la actualización del estado al cargar la página
            $('#tipo_producto_id').trigger('change');
        });
    </script>
@endpush
