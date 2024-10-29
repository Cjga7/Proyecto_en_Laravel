<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Persona;
use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use Exception;

class clienteController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-cliente|crear-cliente|editar-cliente|eliminar-cliente', ['only' => ['index']]);
        $this->middleware('permission:crear-cliente', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-cliente', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-cliente', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Cargar clientes junto con la persona, el documento y las razones sociales
    $clientes = Cliente::with(['persona.documento', 'persona.razonesSociales'])->get();

    return view('cliente.index', compact('clientes'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        return view('cliente.create',compact('documentos'));
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
            $persona->cliente()->create([
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

            return redirect()->route('clientes.index')->with('success', 'Cliente registrado con éxito');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('clientes.index')->with('error', 'Error al registrar el cliente');
        }
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
    public function edit(Cliente $cliente)
    {
        $cliente->load('persona','documento');
        $documento = Documento::all();
        return view('cliente.edit',compact('cliente',  'documento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            DB::beginTransaction();

            // Actualizar la persona asociada al cliente
            $cliente->persona->update($request->validated());

            // Si la persona es jurídica, actualizar las razones sociales
            if ($request->tipo_persona === 'juridica') {
                // Eliminar las razones sociales actuales
                $cliente->persona->razonesSociales()->delete();

                // Volver a crear las razones sociales ingresadas
                foreach ($request->razones_sociales as $razon) {
                    $cliente->persona->razonesSociales()->create([
                        'razon_social' => $razon
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente editado con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('clientes.index')->with('error', 'Error al editar el cliente');
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
            $message = 'Cliente eliminado';
        } else {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Cliente restaurado';
        }

        return redirect()->route('clientes.index')->with('success', $message);
    }
}
