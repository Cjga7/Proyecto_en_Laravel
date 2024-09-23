@extends('layouts.master')

@section('title', 'Crear compra')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear compra</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('compras.index') }}">Compra</a></li>
            <li class="breadcrumb-item active">Crear Compra</li>
        </ol>
    </div>
    <form action="{{ route('compras.store') }}" method="post">
        @csrf

        <div class="container mt-4">
            <div class="row gy-4">
                <!-------Compra Producto---->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalles de la compra
                    </div>
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">
                            <!-------Producto---->
                            <div class="col-md-12 mb-2">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker"
                                    data-live-search="true" data-size='2' title="Busque un producto">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}">{{ $item->codigo . ' ' . $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-------Cantidad---->
                            <div class="col-md-6 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!-------Precio de compra---->
                            <div class="col-md-6 mb-2">
                                <label for="precio_compra" class="form-label">Precio de compra:</label>
                                <input type="number" name="precio_compra" id="precio_compra" class="form-control"
                                    step="0.1">
                            </div>

                            <!-------boton para agregar---->
                            <div class="col-md-12 mb-2 text-end">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>

                            <!-------tabla detalles de la compra---->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio de Compra</th>
                                                <th>SubTotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th colspan="3">Sumas</th>
                                                <th colspan="2"><span id="sumas">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="3">Total</th>
                                                <th colspan="2"> <input type="hidden" name="total" value="0"
                                                        id="inputTotal"><span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!---Boton para cancelar compra---->
                            <div class="col-md-12 mb-2">
                                <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Cancelar compra
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
                <!-------Producto---->
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row">
                            <!-------Proveedor---->
                            <div class="col-md-12 mb-2">
                                <label for="proveedore_id" class="form-label">Proveedor:</label>
                                <select name="proveedore_id" id="proveedore_id" class="form-control selectpicker show-tick"
                                    data-live-search="true" title="Selecciona" data-size='2'>
                                    @foreach ($proveedores as $item)
                                        <option value="{{ $item->id }}">{{ $item->persona->razon_social }}</option>
                                    @endforeach
                                </select>
                                @error('proveedore_id')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-------Tipo de comprobante---->
                            <div class="col-md-12 mb-2">
                                <label for="comprobante_id" class="form-label">Comprobante:</label>
                                <select name="comprobante_id" id="comprobante_id"
                                    class="form-control selectpicker show-tick" title="Selecciona">
                                    @foreach ($comprobantes as $item)
                                        <option value="{{ $item->id }}">{{ $item->tipo_comprobante }}</option>
                                    @endforeach
                                </select>
                                @error('comprobante_id')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-------Numero de comprobante---->
                            <div class="col-md-12 mb-2">
                                <label for="numero_comprobante" class="form-label">Numero de Comprobante:</label>
                                <input required type="text" name="numero_comprobante" id="numero_comprobante"
                                    class="form-control">
                                @error('numero_comprobante')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-------Fecha Hora---->
                            <div class="col-sm-6 mb-2">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha"
                                    class="form-control border-success" value="<?php echo date('Y-m-d'); ?>">
                                <?php

                                use Carbon\Carbon;

                                $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">
                            </div>

                            <!-------Botones---->
                            <div class="col-md-12 mb-2 text-center">
                                <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal para cancelar la  compra -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal de confirmacion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Seguro que quieres cancelar la compra?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarCompra" type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
     $(document).ready(function() {
    $('#btn_agregar').click(function() {
        agregarProducto();
    });

    $('#btnCancelarCompra').click(function() {
        cancelarCompra();
    });
    disableButtons();
});

//variables
let cont = 0;
let subtotal = [];
let sumas = 0;
let total = 0;

function cancelarCompra() {
    // Eliminar todas las filas de la tabla de detalles
    $('#tabla_detalle tbody').empty();

    // Resetear los valores de sumas y total
    $('#sumas').text(0);
    $('#total').text(0);
    $('#inputTotal').val(0);

    // Deshabilitar el botón de guardar
    disableButtons();
}

function agregarProducto() {
    // Obtenemos los valores del formulario
    let producto_id = $('#producto_id').val();
    let cantidad = $('#cantidad').val();
    let precio_compra = $('#precio_compra').val();
    let producto = $('#producto_id option:selected').text();

    // Validación de entradas
    if (producto_id != '' && cantidad != '' && cantidad > 0 && precio_compra != '') {
        subtotal[cont] = cantidad * precio_compra;
        sumas += subtotal[cont];
        total = sumas;

        // Crear una nueva fila en la tabla
        let fila = '<tr id="fila' + cont + '">' +
            '<td>' + (cont + 1) + '</td>' +
            '<td>' + producto + '<input type="hidden" name="producto_id[]" value="' + producto_id + '"></td>' +
            '<td>' + cantidad + '<input type="hidden" name="cantidad[]" value="' + cantidad + '"></td>' +
            '<td>' + precio_compra + '<input type="hidden" name="precio_compra[]" value="' + precio_compra +
            '"></td>' +
            '<td>' + subtotal[cont].toFixed(2) + '</td>' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + cont +
            ')">X</button></td>' +
            '</tr>';

        // Añadir la nueva fila a la tabla
        $('#tabla_detalle tbody').append(fila);

        // Actualizar sumas y total
        $('#sumas').text(sumas.toFixed(2));
        $('#total').text(total.toFixed(2));
        $('#inputTotal').val(total);

        // Incrementar el contador
        cont++;

        // Habilitar los botones de guardar y cancelar
        enableButtons();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Rellene todos los campos del detalle!',
        });
    }
}

function eliminarFila(index) {
    // Restar el subtotal de la fila eliminada de la suma total
    sumas -= subtotal[index];
    total = sumas;

    // Actualizar sumas y total
    $('#sumas').text(sumas.toFixed(2));
    $('#total').text(total.toFixed(2));
    $('#inputTotal').val(total);

    // Eliminar la fila de la tabla
    $('#fila' + index).remove();

    // Si no hay más filas, deshabilitar el botón de guardar
    if ($('#tabla_detalle tbody tr').length == 0) {
        disableButtons();
    }
}

function disableButtons() {
    $('#guardar').attr('disabled', true);
    $('#cancelar').attr('disabled', true);
}

function enableButtons() {
    $('#guardar').attr('disabled', false);
    $('#cancelar').attr('disabled', false);
}

        function showModal(message, icon = 'error') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: icon,
                title: message
            })
        }
    </script>
@endpush
