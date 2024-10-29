<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\DB;

class ventaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta', ['only' => ['index']]);
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-venta', ['only' => ['show']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
    }

    public function index()
    {
        $ventas = Venta::with(['comprobante', 'cliente.persona', 'user'])
            ->where('estado', 1)
            ->latest()
            ->get();

        return view('venta.index', compact('ventas'));
    }

    public function create()
    {
        // Solo traer productos terminados que tengan stock
        $productos = Producto::where('estado', 1)
            ->where('tipo_producto_id', 1) // Filtrar solo productos terminados usando tipo_producto_id
            ->where('stock', '>', 0)
            ->get();

        $clientes = Cliente::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();

        $comprobantes = Comprobante::all();

        return view('venta.create', compact('productos', 'clientes', 'comprobantes'));
    }


    public function store(StoreVentaRequest $request)
{
    try {
        DB::beginTransaction();

        // Crear registro de venta con datos iniciales, pero sin asignar aún el total
        $venta = Venta::create($request->validated());

        // Recuperar arrays de productos, cantidades y descuentos
        $arrayProducto_id = $request->get('arrayidproducto');
        $arrayCantidad = $request->get('arraycantidad');
        $arrayDescuento = $request->get('arraydescuento');

        // Variable para acumular el total de la venta
        $totalVenta = 0;

        // Procesar cada producto vendido
        $sizeArray = count($arrayProducto_id);
        for ($cont = 0; $cont < $sizeArray; $cont++) {
            $producto = Producto::findOrFail($arrayProducto_id[$cont]);
            $cantidad = intval($arrayCantidad[$cont]);

            // Asegurarse de que hay suficiente stock
            if ($producto->stock < $cantidad) {
                throw new Exception('Stock insuficiente para el producto: ' . $producto->nombre);
            }

            // Obtener el precio y descuento
            $precioVenta = $producto->precio_venta;
            $descuento = $arrayDescuento[$cont] ?? 0;

            // Calcular el subtotal del producto en la venta y agregarlo al total
            $subtotalProducto = ($precioVenta * $cantidad) - $descuento;
            $totalVenta += $subtotalProducto;

            // Registrar el producto en la venta con attach, incluyendo cantidad, precio y descuento
            $venta->productos()->attach($producto->id, [
                'cantidad' => $cantidad,
                'precio_venta' => $precioVenta,
                'descuento' => $descuento
            ]);

            // Actualizar el stock del producto
            $producto->stock -= $cantidad;
            $producto->save();
        }

        // Asignar el total de la venta y guardar la venta
        $venta->total = $totalVenta;
        $venta->save();

        DB::commit();
        return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente.');
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->route('ventas.index')->with('error', 'Error al registrar la venta: ' . $e->getMessage());
    }
}





    public function show(Venta $venta)
    {
        return view('venta.show', compact('venta'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        Venta::where('id', $id)
            ->update([
                'estado' => 0
            ]);
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada');
    }
}
