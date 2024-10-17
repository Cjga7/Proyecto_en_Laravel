<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
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
        return [
            'nombre' => 'required|max:50',  // Siempre requerido para ambos tipos
            'primer_apellido' => 'required|max:50',  // Siempre requerido para ambos tipos
            'segundo_apellido' => 'nullable|max:50',
            'razon_social' => 'nullable|max:80|required_if:tipo_persona,juridica|unique:personas,razon_social',
            'direccion' => 'required|max:80',
            'tipo_persona' => 'required|string',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|max:20|unique:personas,numero_documento',
            'telefono' => 'nullable|integer', // Validación para el teléfono
            'correo_electronico' => 'nullable|email|max:100|unique:personas,correo_electronico' // Validación para el correo electrónico
        ];
    }

}
