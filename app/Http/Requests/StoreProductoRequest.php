<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
        $rules = [
            'codigo' => 'required|unique:productos,codigo|max:50',
            'nombre' => 'required|unique:productos,nombre|max:80',
            'descripcion' => 'nullable|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categorias' => 'required',
            'tipo_producto_id' => 'required|integer|exists:tipos_productos,id',
        ];

        // Validaciones adicionales solo para productos terminados (ID = 1)
        if ($this->input('tipo_producto_id') == 1) {
            $rules['registrosanitario_id'] = 'required|integer|exists:registrosanitarios,id';
            $rules['precio_venta'] = 'required|numeric|min:0';
        }

        return $rules;
    }
    public function attributes()
    {
        return [
            'registrosanitario_id' => 'registro sanitario',
            'presentacione_id' => 'presentación',
            'tipo_producto_id' => 'tipo de producto',
            'precio_venta' => 'precio de venta',
        ];
    }
}
