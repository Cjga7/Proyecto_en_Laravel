<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProveedoreRequest extends FormRequest
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
        $proveedore = $this->route('proveedore');
        return [
            'nombre' => 'required|max:50',
            'primer_apellido' => 'required|max:50',
            'segundo_apellido' => 'nullable|max:50',
            'direccion' => 'required|max:80',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|min:8|max:20|unique:personas,numero_documento,' . $proveedore->persona->id,
            'tipo_persona' => 'required|string',
            'telefono' => 'nullable|integer',  // Más flexible para distintos formatos
            'correo_electronico' => 'nullable|email|max:100|unique:personas,correo_electronico,' . $proveedore->persona->id,

            // Validación para las razones sociales de las personas jurídicas
           // 'razones_sociales' => 'nullable|array|required_if:tipo_persona,juridica|min:1',
            //'razones_sociales.*' => 'string|max:255|distinct',
        ];
    }

    /**
     * Configurar validaciones condicionales adicionales.
     */
    public function withValidator($validator)
    {
        // Validar razones sociales solo si es persona jurídica
        $validator->sometimes('razones_sociales', 'required|array|min:1', function ($input) {
            return $input->tipo_persona === 'juridica';
        });

        $validator->sometimes('razones_sociales.*', 'string|max:255|distinct', function ($input) {
            return $input->tipo_persona === 'juridica';
        });
    }
}
