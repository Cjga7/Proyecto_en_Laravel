<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Registrosanitario;
use App\Models\Presentacione;
use App\Models\Producto;
use Exception;
use App\Models\TipoProducto;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importar DB
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class ProductoController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-producto|crear-producto|editar-producto|eliminar-producto', ['only' => ['index']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['categorias.caracteristica', 'registrosanitario.caracteristica', 'presentacione.caracteristica', 'tipoProducto'])->latest()->get();
        return view('producto.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $registrosanitarios = Registrosanitario::join('caracteristicas as c', 'registrosanitarios.caracteristica_id', '=', 'c.id')
            ->select('registrosanitarios.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();
            $tiposProductos = TipoProducto::all(); // Obtener todos los tipos de productos

            // Retornar la vista con todas las variables correctamente definidas
            return view('producto.create', compact('registrosanitarios', 'presentaciones', 'categorias', 'tiposProductos'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            DB::beginTransaction();
            //tabla producto
            $producto = new Producto();
            if ($request->hasFile('img_path')) {
                $name = $producto->hanbleUploadImage($request->file('img_path'));
            } else {
                $name = null;
            }


            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'registrosanitario_id' => $request->registrosanitario_id,
                'presentacione_id' => $request->presentacione_id,
                'tipo_producto_id' => $request->tipo_producto_id

            ]);
            $producto->save();
            //tabla categoria producto
            $categorias = $request->get('categorias');
            $producto->categorias()->attach($categorias);



            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        return redirect()->route('productos.index')->with('success', 'Producto Registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $registrosanitarios = Registrosanitario::join('caracteristicas as c', 'registrosanitarios.caracteristica_id', '=', 'c.id')
            ->select('registrosanitarios.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();
        $tiposProductos = TipoProducto::all(); // Obtener todos los tipos de productos
        return view('producto.edit', compact('producto', 'registrosanitarios', 'presentaciones', 'categorias', 'tiposProductos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try {
            DB::beginTransaction();

            //tabla producto
            if ($request->hasFile('img_path')) {
                $name = $producto->hanbleUploadImage($request->file('img_path'));

                //Eliminar imagen existente
                if(Storage::disk('public')->exists('productos/'.$producto->img_path)){
                    Storage::disk('public')->delete('productos/'.$producto->img_path);
                }
            } else {
                $name = $producto->img_path;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'registrosanitario_id' => $request->registrosanitario_id,
                'presentacione_id' => $request->presentacione_id,
                'tipo_producto_id' => $request->tipo_producto_id
            ]);
            $producto->save();
            //tabla categoria producto
            //sync elimina las anteriores y despues anade
            $categorias = $request->get('categorias');
            $producto->categorias()->sync($categorias);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('productos.index')->with('success','Producto Editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Producto eliminado';
        } else {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Producto restaurado';
        }

        return redirect()->route('productos.index')->with('success', $message);
    }
}
