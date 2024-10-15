@extends('layouts.master')
@section('title')
    Ver Venta
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Ventas @endslot
        @slot('title') Ver Venta @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Título de la venta -->
                    <div class="invoice-title">
                        <h4 class="float-end font-size-16">Venta #{{ $venta->numero_comprobante }} <span
                                class="badge bg-success font-size-12 ms-2">{{ $venta->estado }}</span></h4>
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

                    <!-- Información del cliente -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-muted">

                                    <h5 class="font-size-16 mb-3">Detalle a:</h5>
                                    <h5 class="font-size-15 mb-2">{{ $venta->cliente->persona->nombre }} {{ $venta->cliente->persona->primer_apellido }} {{ $venta->cliente->persona->segundo_apellido ?? '' }}</h5>
                                    <p class="mb-1">{{ $venta->cliente->persona->direccion }}</p>
                                    <p class="mb-1">{{ $venta->cliente->persona->razon_social ?? 'N/A' }}</p>
                                    <p>{{ $venta->cliente->persona->numero_documento }}</p>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted text-sm-end">
                                <div>
                                    <h5 class="font-size-16 mb-1">Número de Venta:</h5>
                                    <p>#{{ $venta->numero_comprobante }}</p>
                                </div>
                                <div class="mt-4">
                                    <h5 class="font-size-16 mb-1">Fecha de Venta:</h5>
                                    <p>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d M, Y') }}</p>
                                </div>
                                <div>
                                    <h5 class="font-size-16 mb-1">Vendedor:</h5>
                                    <p>{{ $venta->user->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de la venta -->
                    <div class="py-2">
                        <h5 class="font-size-15">Resumen de la venta</h5>

                        <div class="table-responsive">
                            <table class="table table-nowrap table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">No.</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio de Venta</th>
                                        <th class="text-end" style="width: 120px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($venta->productos as $key => $item)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            <h5 class="font-size-15 mb-1">{{ $item->nombre }}</h5>
                                        </td>
                                        <td>{{ $item->pivot->cantidad }}</td>
                                        <td>Bs.{{ $item->pivot->precio_venta }}</td>
                                        <td class="text-end">Bs.{{ $item->pivot->cantidad * $item->pivot->precio_venta - $item->pivot->descuento }}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Subtotal</th>
                                        <td class="text-end">Bs.{{ $venta->productos->sum(function($producto) { return $producto->pivot->cantidad * $producto->pivot->precio_venta - $producto->pivot->descuento; }) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">Impuesto:</th>
                                        <td class="border-0 text-end">Bs.{{ $venta->impuesto ?? '0.00' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="border-0 text-end">Total:</th>
                                        <td class="border-0 text-end">
                                            <h4 class="m-0">Bs.{{ $venta->total }}</h4>
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
