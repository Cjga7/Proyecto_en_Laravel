@extends('template')

@section('title', 'Crear compra')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
    <form action="" method="post">
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
                                <select name="producto_id" id="producto_id" class="form-control selectpicker "
                                    data-live-search="true" data-size='1' title="Busque un producto">
                                    @foreach ($productos as $item)
                                        1
                                        <option value="{{ $item->id }}">{{ $item->codigo . '  ' . $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-------Cantidad---->
                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!-------Precio de compra---->
                            <div class="col-md-4 mb-2">
                                <label for="precio_compra" class="form-label">Precio de compra:</label>
                                <input type="number" name="precio_compra" id="precio_compra" class="form-control"
                                    step="0.1">
                            </div>

                            <!-------Precio de venta---->
                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-label">Precio de venta:</label>
                                <input type="number" name="precio_venta" id="precio_venta" class="form-control"
                                    step="0.1">
                            </div>
                            <!-------boton para agregar---->
                            <div class="col-md-12 mb-2 text-end">
                                <button class="btn btn-primary" type="button">Agregar</button>
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
                                                <th>Precio de venta</th>
                                                <th>SubTotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>Sumas</th>
                                                <th>0</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>IGV %</th>
                                                <th>0</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Total</th>
                                                <th>0</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
                            </div>

                            <!-------Numero de comprobante---->
                            <div class="col-md-12 mb-2">
                                <label for="numero_comprobante" class="form-label">Numero de Comprobante:</label>
                                <input required type="text" name="numero_comprobante" id="numero_comprobante"
                                    class="form-control">
                            </div>
                            <!-------Impuesto---->
                            <div class="col-md-6 mb-2">
                                <label for="impuesto" class="form-label">Impuesto(IGV):</label>
                                <input readonly type="text" name="impuesto" id="impuesto"
                                    class="form-control border-success">
                            </div>

                            <!-------Fecha Hora---->
                            <div class="col-md-6 mb-2">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha"
                                    class="form-control border-success" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-------Botones---->
                            <div class="col-md-12 mb-2 text-center">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
