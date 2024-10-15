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
            'nombre' => 'required|max:50',  // Siempre requerido
            'primer_apellido' => 'required|max:50',  // Siempre requerido
            'segundo_apellido' => 'nullable|max:50',
            'razon_social' => 'nullable|max:80|required_if:tipo_persona,juridica',
            'direccion' => 'required|max:80',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|max:20|unique:personas,numero_documento,' . $cliente->persona->id,
            'tipo_persona' => 'required|string',
        ];
    }
}
