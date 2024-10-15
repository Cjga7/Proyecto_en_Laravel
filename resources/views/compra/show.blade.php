@extends('layouts.master')
@section('title')
    Detalle de Compra
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Compras @endslot
        @slot('title') Detalle de Compra @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Título de la compra -->
                    <div class="invoice-title">
                        <h4 class="float-end font-size-16">Compra #{{ $compra->numero_comprobante }} <span
                                class="badge bg-success font-size-12 ms-2">{{ $compra->estado }}</span></h4>
                        <div class="mb-4">
                            <img src="{{ URL::asset('/assets/images/Logo_lanago.png') }}" alt="logo" height="200" />
                        </div>

                        <div class="text-muted">
                            <p class="mb-1">Calle Ficticia 123, Ciudad de Ejemplo</p>
                            <p class="mb-1"><i class="uil uil-envelope-alt me-1"></i> contacto@lanago.com</p>
                            <p><i class="uil uil-phone me-1"></i> +1 800 123 4567</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Información de facturación -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-muted">
                                <h5 class="font-size-16 mb-3">Detalle a:</h5>
                                <h5 class="font-size-15 mb-2">{{ $compra->proveedore->persona->nombre }} {{ $compra->proveedore->persona->primer_apellido }} {{ $compra->proveedore->persona->segundo_apellido ?? '' }}</h5>
                                <p class="mb-1">{{ $compra->proveedore->persona->direccion }}</p>
                                <p class="mb-1">{{ $compra->proveedore->persona->razon_social ?? 'N/A' }}</p>
                                <p>{{ $compra->proveedore->persona->numero_documento }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted text-sm-end">
                                <div>
                                    <h5 class="font-size-16 mb-1">Número de Compra:</h5>
                                    <p>#{{ $compra->numero_comprobante }}</p>
                                </div>
                                <div class="mt-4">
                                    <h5 class="font-size-16 mb-1">Fecha de Compra:</h5>
                                    <p>{{ \Carbon\Carbon::parse($compra->fecha_hora)->format('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del pedido -->
                    <div class="py-2">
                        <h5 class="font-size-15">Resumen de la compra</h5>

                        <div class="table-responsive">
                            <table class="table table-nowrap table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">No.</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th class="text-end" style="width: 120px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($compra->productos as $key => $producto)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            <h5 class="font-size-15 mb-1">{{ $producto->nombre }}</h5>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item">Descripción: <span class="fw-medium">{{ $producto->descripcion }}</span></li>
                                            </ul>
                                        </td>
                                        <td>Bs.{{ $producto->pivot->precio_compra }}</td>
                                        <td>{{ $producto->pivot->cantidad }}</td>
                                        <td class="text-end">Bs.{{ $producto->pivot->precio_compra * $producto->pivot->cantidad }}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Subtotal</th>
                                        <td class="text-end">Bs.{{ $compra->productos->sum(function($producto) { return $producto->pivot->cantidad * $producto->pivot->precio_compra; }) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">Descuento:</th>
                                        <td class="border-0 text-end">- Bs.{{ $compra->descuento ?? '0.00' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">Impuesto:</th>
                                        <td class="border-0 text-end">Bs.{{ $compra->impuesto ?? '0.00' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">Total:</th>
                                        <td class="border-0 text-end">
                                            <h4 class="m-0">Bs.{{ $compra->total }}</h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-print-none mt-4">
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1">
                                    <i class="fa fa-print"></i> Imprimir
                                </a>
                                <a href="#" class="btn btn-primary w-md waves-effect waves-light">Enviar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

@endsection
