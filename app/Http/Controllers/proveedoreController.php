<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedoreRequest;
use App\Models\Proveedore;
use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use Exception;

class proveedoreController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-proveedore|crear-proveedore|editar-proveedore|eliminar-proveedore', ['only' => ['index']]);
        $this->middleware('permission:crear-proveedore', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-proveedore', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-proveedore', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedore::with('persona.documento')->get();

        return view('proveedore.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        return view('proveedore.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            DB::beginTransaction();

            // Crear la persona
            $data = $request->validated();

            // Si es una persona natural, eliminar razon_social de los datos.
            if ($data['tipo_persona'] === 'natural') {
                unset($data['razon_social']); // Eliminar razon_social si es persona natural
            }

            $persona = Persona::create($data);

            // Crear el proveedor
            $persona->proveedore()->create([
                'persona_id' => $persona->id
            ]);

            DB::commit();

            return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Error al registrar el proveedor: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedore $proveedore)
    {
        $proveedore->load('persona', 'documento');
        $documento = Documento::all();
        return view('proveedore.edit', compact('proveedore',  'documento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedoreRequest $request, Proveedore $proveedore)
{
    try {
        DB::beginTransaction();

        Persona::where('id', $proveedore->persona->id)
            ->update($request->validated());

        DB::commit();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor Editado');
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Error al editar el proveedor.'])->withInput();
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $persona = Persona::find($id);
        if ($persona->estado == 1) {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Proveedor eliminado';
        } else {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Proveedor restaurado';
        }

        return redirect()->route('proveedores.index')->with('success', $message);
    }
}
