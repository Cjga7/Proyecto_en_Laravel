<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
            $producto = $this->route('producto');
        return [
            'codigo' => 'required|unique:productos,codigo,'.$producto->id.'|max:50',
            'nombre' => 'required|unique:productos,nombre,'.$producto->id.'|max:80',
            'descripcion' => 'nullable|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'registrosanitario_id' => 'required|integer|exists:registrosanitarios,id',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categorias' => 'required',
            'tipo_producto_id' => 'required|integer|exists:tipos_productos,id' // Nueva regla de validación
        ];
    }
    public function attributes()
    {
        return[
            'registrosanitario_id' => 'registrosanitario',
            'presentacione_id' => 'presentacion',
            'tipo_producto_id' => 'tipo de producto'// Nombre personalizado para el atributo
        ];
    }

    public function messages()
    {
        return [
            'codigo.required'=> 'Se necesita un campo codigo'
        ];
    }
}
