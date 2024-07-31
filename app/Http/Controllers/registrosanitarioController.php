<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaracteristicaRequest;
use App\Http\Requests\UpdateRegistrosanitarioRequest;
use App\Models\Caracteristica;
use App\Models\Registrosanitario;
use Exception;
use Illuminate\Support\Facades\DB;

class registrosanitarioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-registrosanitario|crear-registrosanitario|editar-registrosanitario|eliminar-registrosanitario', ['only' => ['index']]);
        $this->middleware('permission:crear-registrosanitario', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-registrosanitario', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-registrosanitario', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrosanitarios = Registrosanitario::with('caracteristica')->latest()->get();
        return view('registrosanitario.index',compact('registrosanitarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registrosanitario.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaracteristicaRequest $request)
    {

        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->registrosanitario()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('registrosanitarios.index')->with('success', 'registro sanitario registrada');
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
    public function edit(Registrosanitario $registrosanitario)
    {
        return view('registrosanitario.edit',compact('registrosanitario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegistrosanitarioRequest $request, Registrosanitario $registrosanitario)
    {
        Caracteristica::where('id', $registrosanitario->caracteristica->id)
            ->update($request->validated());

        return redirect()->route('registrosanitarios.index')->with('success', 'registro sanitario editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $registrosanitario = Registrosanitario::find($id);
        if ($registrosanitario->caracteristica->estado == 1) {
            Caracteristica::where('id', $registrosanitario->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'registro sanitario eliminada';
        } else {
            Caracteristica::where('id', $registrosanitario->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);
            $message = ' registro sanitario restaurada';
        }

        return redirect()->route('registrosanitarios.index')->with('success', $message);
    }
}
