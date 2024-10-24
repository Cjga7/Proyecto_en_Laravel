@extends('layouts.master')

@section('title', 'Ver compra')

@push('css')
<style>
    @media (max-width:575px) {
        #hide-group {
            display: none;
        }
    }
    @media (min-width:576px) {
        #icon-form {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="mt-4 text-center">Ver Compra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
        <li class="breadcrumb-item active">Ver Compra</li>
    </ol>
</div>

<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">Datos generales de la compra</div>
        <div class="card-body">

            <!-- Información General -->
            @php
                $fields = [
                    'Tipo de comprobante' => $compra->comprobante->tipo_comprobante,
                    'Número de comprobante' => $compra->numero_comprobante,
                    'Proveedor' => $compra->proveedore->persona->razon_social,
                    'Fecha' => \Carbon\Carbon::parse($compra->fecha_hora)->format('d-m-Y'),
                    'Hora' => \Carbon\Carbon::parse($compra->fecha_hora)->format('H:i'),
                    'Impuesto' => $compra->impuesto
                ];
            @endphp

            @foreach ($fields as $label => $value)
            <div class="row mb-4">
                <div class="col-sm-6">
                    <div class="input-group" id="hide-group">
                        <span class="input-group-text"><i class="fa-solid fa-file"></i></span>
                        <input disabled type="text" class="form-control" value="{{ $label }}:">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <span title="{{ $label }}" id="icon-form" class="input-group-text">
                            <i class="fa-solid fa-info-circle"></i>
                        </span>
                        <input disabled type="text" class="form-control" value="{{ $value }}">
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <!-- Detalle de la compra -->
    <div class="card mb-2">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Detalle de la compra
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary">
                    <tr class="align-top text-white">
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio de compra</th>
                        <th>Precio de venta</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compra->productos as $item)
                    <tr>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->pivot->cantidad }}</td>
                        <td>{{ number_format($item->pivot->precio_compra, 2) }}</td>
                        <td>{{ number_format($item->pivot->precio_venta, 2) }}</td>
                        <td class="td-subtotal">{{ number_format($item->pivot->cantidad * $item->pivot->precio_compra, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Sumas:</th>
                        <th id="th-suma"></th>
                    </tr>
                    <tr>
                        <th colspan="4">IGV:</th>
                        <th id="th-igv"></th>
                    </tr>
                    <tr>
                        <th colspan="4">Total:</th>
                        <th id="th-total"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        calcularValores();
    });

    function calcularValores() {
        let totalSubtotal = 0;
        let impuesto = parseFloat($('#input-impuesto').val());
        $('.td-subtotal').each(function() {
            totalSubtotal += parseFloat($(this).text().replace(',', ''));
        });

        $('#th-suma').text(totalSubtotal.toFixed(2));
        $('#th-igv').text(impuesto.toFixed(2));
        $('#th-total').text((totalSubtotal + impuesto).toFixed(2));
    }
</script>
@endpush
