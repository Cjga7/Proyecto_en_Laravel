<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;

class homeController extends Controller
{
    public function index()
    {
        // Consulta de productos más vendidos por cada mes
        $ventasMensuales = Producto::select(
                DB::raw('MONTH(producto_venta.created_at) as mes'),
                'productos.nombre',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido')
            )
            ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
            ->groupBy('mes', 'productos.nombre')
            ->orderBy('mes')
            ->get();

        // Organiza los datos para el gráfico
        $meses = [];
        $productosData = [];

        foreach ($ventasMensuales as $venta) {
            $meses[$venta->mes] = $venta->mes;
            $productosData[$venta->nombre][$venta->mes] = $venta->total_vendido;
        }

        return view('panel.index', compact('meses', 'productosData'));
    }
}

