@extends('layouts.master')

@section('title', 'Ajustar Stock')

@push('css')
    <style>
        #descripcion {
            resize: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Ajustar Stock</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">Ajustar Stock</li>
        </ol>
        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('productos.ajustarStock') }}" method="post">
                @csrf
                <div class="row g-3">

                    <!-- Producto -->
                    <div class="col-md-6 mb-2">
                        <label for="producto_id" class="form-label">Producto:</label>
                        <select name="producto_id" id="producto_id" class="form-control selectpicker show-tick" required>
                            <option value="">Seleccione un producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}"
                                        data-stock="{{ $producto->stock }}"
                                        data-tipo="{{ $producto->tipoProducto->nombre }}">
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="stock_actual" name="stock_actual">
                        @error('producto_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Stock actual -->
                    <div class="col-md-6 mb-2">
                        <label for="stock_display" class="form-label">Stock Actual:</label>
                        <input type="text" id="stock_display" class="form-control" value="Seleccione un producto" readonly>
                    </div>

                    <!-- Tipo de producto -->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_producto_display" class="form-label">Tipo de Producto:</label>
                        <input type="text" id="tipo_producto_display" class="form-control" value="Seleccione un producto" readonly>
                    </div>

                    <!-- Cantidad -->
                    <div class="col-md-6 mb-2">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                        @error('cantidad')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!-- Tipo de ajuste -->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_ajuste" class="form-label">Tipo de ajuste:</label>
                        <select name="tipo_ajuste" id="tipo_ajuste" class="form-control" required>
                            <option value="incrementar">Incrementar</option>
                            <option value="disminuir">Disminuir</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Ajustar Stock</button>
                    </div>

                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Al cambiar el producto, actualizar el stock actual y el tipo de producto
            $('#producto_id').change(function() {
                var stock = $(this).find('option:selected').data('stock') || 0;
                var tipoProducto = $(this).find('option:selected').data('tipo') || 'N/A';

                $('#stock_actual').val(stock);
                $('#stock_display').val(stock + ' unidades');
                $('#tipo_producto_display').val(tipoProducto);
            });

            // Validación al enviar el formulario
            $('form').submit(function(event) {
                var stockActual = parseInt($('#stock_actual').val());
                var cantidad = parseInt($('#cantidad').val());
                var tipoAjuste = $('#tipo_ajuste').val();

                if (tipoAjuste === 'disminuir' && (stockActual - cantidad) < 0) {
                    event.preventDefault(); // Prevenir el envío del formulario
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No puedes disminuir el stock por debajo de 0.',
                        confirmButtonText: 'Entendido'
                    });
                }
            });
        });
    </script>
@endpush
