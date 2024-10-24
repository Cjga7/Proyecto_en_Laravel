<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $cliente = $this->route('cliente');
        return [
            'nombre' => 'required|max:50',
            'primer_apellido' => 'required|max:50',
            'segundo_apellido' => 'nullable|max:50',
            'razon_social' => 'nullable|max:80|unique:personas,razon_social,' . $cliente->persona->id . '|required_if:tipo_persona,juridica',
            'direccion' => 'required|max:80',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|min:8|max:20|unique:personas,numero_documento,' . $cliente->persona->id,
            'tipo_persona' => 'required|string',
            'telefono' => 'nullable|integer|',  // Más flexible para distintos formatos
            'correo_electronico' => 'nullable|email|max:100|unique:personas,correo_electronico,' . $cliente->persona->id,
        ];
    }
}
