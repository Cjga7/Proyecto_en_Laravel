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
        $proveedores = Proveedore::with('persona.documento', 'persona.razonesSociales')->get();

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
            $persona = Persona::create($request->validated());

            // Asociar la persona creada con el cliente
            $persona->proveedore()->create([
                'persona_id' => $persona->id
            ]);

            // Si es de tipo jurídica, agregar las razones sociales
            if ($request->tipo_persona === 'juridica' && $request->has('razones_sociales')) {
                foreach ($request->razones_sociales as $razon) {
                    $persona->razonesSociales()->create([
                        'razon_social' => $razon
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado con éxito');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('proveedores.index')->with('error', 'Error al registrar el Proveedor');
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

            // Actualizar la persona asociada al proveedore
            $proveedore->persona->update($request->validated());

            // Si la persona es jurídica, actualizar las razones sociales
            if ($request->tipo_persona === 'juridica') {
                // Eliminar las razones sociales actuales
                $proveedore->persona->razonesSociales()->delete();

                // Volver a crear las razones sociales ingresadas
                foreach ($request->razones_sociales as $razon) {
                    $proveedore->persona->razonesSociales()->create([
                        'razon_social' => $razon
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor editado con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('proveedores.index')->with('error', 'Error al editar el proveedore');
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
