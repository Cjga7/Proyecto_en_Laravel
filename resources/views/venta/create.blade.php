@extends('layouts.master')

@section('title', 'Realizar venta')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Venta</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('ventas.index') }}">Ventas</a></li>
            <li class="breadcrumb-item active">Raelizar Venta</li>
        </ol>
    </div>

    <form action="{{ route('ventas.store') }}" method="post">
        @csrf

        <div class="container mt-4">
            <div class="row gy-4">
                <!-------venta Producto---->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalles de la venta
                    </div>
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">
                            <!-------Producto---->
                            <div class="col-md-12 mb-2">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker "
                                    data-live-search="true" data-size='3' title="Busque un producto">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}-{{ $item->stock }}-{{ $item->precio_venta }}">
                                            {{ $item->codigo . ' ' . $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!--Stock---->
                            <div class="d-flex justify-content-end mb-4 ">
                                <div class="col-md-6 mb-2">
                                    <div class="row">
                                        <label for="stock" class="form-label col-sm-4">en Stock:</label>
                                        <div class="col-sm-8">
                                            <input disabled type="number" name="stock" id="stock"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-------Cantidad---->
                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!-------Precio de venta---->
                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-label">Precio de venta:</label>
                                <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control"
                                    step="0.1">
                            </div>

                            <!-------Descuento---->
                            <div class="col-md-4 mb-2">
                                <label for="descuento" class="form-label">Descuento:</label>
                                <input type="number" name="descuento" id="descuento" class="form-control">
                            </div>
                            <!-------boton para agregar---->
                            <div class="col-md-12 mb-2 text-end">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>

                            <!-------tabla detalles de la venta---->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio de venta</th>
                                                <th>Descuento</th>
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
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Sumas</th>
                                                <th colspan="2"><span id="sumas">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2"> <input type="hidden" name="total" value="0"
                                                        id="inputTotal"><span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!---Boton para cancelar venta---->
                            <div class="col-md-12 mb-2">
                                <button id="cancelar" type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Cancelar venta
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
                <!-------Venta---->
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row">
                            <!-------Cliente---->
                            <div class="col-md-12 mb-2">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick"
                                    data-live-search="true" title="Selecciona" data-size='2'>
                                    @foreach ($clientes as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->persona->nombre }} {{ $item->persona->primer_apellido }}
                                        @if($item->persona->razon_social)
                                            ({{ $item->persona->razon_social }})
                                        @endif
                                    </option>
                                @endforeach

                                </select>
                                @error('cliente_id')
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

                            <!-------Fecha---->
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

                            <!-------user---->

                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                            <!-------Botones---->
                            <div class="col-md-12 mb-2 text-center">
                                <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal para cancelar la  venta -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Seguro que quieres cancelar la venta?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarVenta" type="button" class="btn btn-danger">Si, cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
<script>
    function calcularTotal() {
        let total = 0;
        let filas = $("#tabla_detalle tbody tr");
        filas.each(function() {
            // Asegurarse de que el valor sea un número válido. Si no lo es, se considera 0.
            let subtotal = parseFloat($(this).find("td").eq(5).text()) || 0;
            total += subtotal;
        });

        $("#sumas").text(total.toFixed(2));
        $("#total").text(total.toFixed(2));
        $("#inputTotal").val(total.toFixed(2));
    }

    $("#producto_id").change(function(e) {
        let productoSeleccionado = $(this).val();
        if (productoSeleccionado != null) {
            let productoInfo = productoSeleccionado.split("-");
            let stock = productoInfo[1];
            let precioVenta = productoInfo[2];
            $("#stock").val(stock);
            $("#precio_venta").val(precioVenta);
        } else {
            $("#stock").val('');
            $("#precio_venta").val('');
        }
    });

    $("#btn_agregar").click(function(e) {
    let productoSeleccionado = $("#producto_id").val();
    if (productoSeleccionado != null) {
        let productoInfo = productoSeleccionado.split("-");
        let productoId = productoInfo[0];
        let stock = parseInt(productoInfo[1]);
        let precioVenta = parseFloat(productoInfo[2]);

        let cantidad = parseInt($("#cantidad").val()) || 0; // Asegurarse de que sea un número válido
        let descuento = parseFloat($("#descuento").val()) || 0; // Asegurarse de que sea un número válido

        if (cantidad <= stock && cantidad > 0) {
            let subtotal = (precioVenta * cantidad) - descuento;

            let fila = `
            <tr>
                <td>${productoId}</td>
                <td>${$("#producto_id option:selected").text()}</td>
                <td>${cantidad}</td>
                <td>${precioVenta.toFixed(2)}</td>
                <td>${descuento.toFixed(2)}</td>
                <td>${subtotal.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm btnEliminar">Eliminar</button></td>
                <!-- Inputs ocultos para enviar al backend -->
                <input type="hidden" name="arrayidproducto[]" value="${productoId}">
                <input type="hidden" name="arraycantidad[]" value="${cantidad}">
                <input type="hidden" name="arraydescuento[]" value="${descuento}">
            </tr>
            `;

            $("#tabla_detalle tbody").append(fila);

            // Limpiar los campos después de agregar el producto
            $("#producto_id").val('');
            $("#cantidad").val('');
            $("#precio_venta").val('');
            $("#descuento").val('');
            $("#stock").val('');
            $("#producto_id").selectpicker('refresh');

            calcularTotal();
        } else {
            alert("Cantidad no válida o insuficiente.");
        }
    } else {
        alert("Seleccione un producto.");
    }
});


    $(document).on("click", ".btnEliminar", function() {
        $(this).closest("tr").remove();
        calcularTotal();
    });

    $("#btnCancelarVenta").click(function(e) {
        window.location.reload();
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
