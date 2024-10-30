<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompraRequest;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Models\Compra;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class compraController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-compra|crear-compra|mostrar-compra|eliminar-compra', ['only' => ['index']]);
        $this->middleware('permission:crear-compra', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-compra', ['only' => ['show']]);
        $this->middleware('permission:eliminar-compra', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('comprobante','proveedore.persona')
        ->where('estado',1)
        ->latest()
        ->get();
        return view('compra.index',compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $proveedores = Proveedore::whereHas('persona', function ($query) {
        $query->where('estado', 1);
    })->get();
    $comprobantes = Comprobante::all();

    // Filtrar solo productos de tipo materia prima (por ejemplo, tipo_producto_id = 1)
    $productos = Producto::where('estado', 1)
                ->where('tipo_producto_id', 2) // Asegúrate de que 2 corresponda a "materia prima"
                ->get();

    return view('compra.create', compact('proveedores', 'comprobantes', 'productos'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
    {
        try {

            DB::beginTransaction();

            // Recoger los datos
            $arrayProducto_id = $request->get('producto_id');
            $arrayCantidad = $request->get('cantidad');
            $arrayPrecioCompra = $request->get('precio_compra');

            // Validar que todos los productos sean de materia prima
            foreach ($arrayProducto_id as $producto_id) {
                $producto = Producto::find($producto_id);
                if ($producto->tipo_producto_id !== 2) { // Asegúrate de que 2 corresponda a "materia prima"
                    throw new Exception("Solo se pueden registrar compras de productos de materia prima.");
                }
            }

            // Crear la compra
            $compra = Compra::create($request->except(['producto_id', 'cantidad', 'precio_compra']));

            // Llenar tabla compra_producto y actualizar stock
            for ($cont = 0; $cont < count($arrayProducto_id); $cont++) {
                $compra->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_compra' => $arrayPrecioCompra[$cont]
                    ]
                ]);

                $producto = Producto::find($arrayProducto_id[$cont]);
                $producto->increment('stock', intval($arrayCantidad[$cont]));
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('compras.index')->with('success', 'Compra exitosa');
    }


    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        return view('compra.show',compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        DB::beginTransaction();

        // Obtener la compra que se va a anular
        $compra = Compra::findOrFail($id);

        // Verificar que la compra está activa (estado 1) antes de actualizar el stock
        if ($compra->estado === 1) {
            // Obtener los productos comprados en la compra
            $productosComprados = DB::table('compra_producto')
                ->where('compra_id', $id)
                ->get();

            // Decrementar el stock de cada producto comprado
            foreach ($productosComprados as $productoComprado) {
                $producto = Producto::findOrFail($productoComprado->producto_id);
                $producto->decrement('stock', intval($productoComprado->cantidad));
            }

            // Cambiar el estado de la compra a 0 para marcarla como anulada
            $compra->estado = 0;
            $compra->save();

            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Compra anulada y stock actualizado');
        } else {
            return redirect()->route('compras.index')->with('error', 'La compra ya está anulada.');
        }
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->route('compras.index')->with('error', 'Error al anular la compra: ' . $e->getMessage());
    }
}

}
