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

    // Obtener el tipo de producto enviado
    $tipoProductoId = $this->input('tipo_producto_id');

    return [
        'codigo' => 'required|unique:productos,codigo,' . $producto->id . '|max:50',
        'nombre' => 'required|unique:productos,nombre,' . $producto->id . '|max:80',
        'descripcion' => 'nullable|max:255',
        'fecha_vencimiento' => 'nullable|date',
        'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        'registrosanitario_id' => ($tipoProductoId == 1 ? 'required' : 'nullable') . '|integer|exists:registrosanitarios,id', // Solo obligatorio para productos terminados
        'presentacione_id' => 'required|integer|exists:presentaciones,id',
        'categorias' => 'required',
        'tipo_producto_id' => 'required|integer|exists:tipos_productos,id',
        'precio_venta' => ($tipoProductoId == 1 ? 'required' : 'nullable') . '|numeric|min:0', // Solo obligatorio para productos terminados
    ];
}

    public function attributes()
    {
        return[
            'registrosanitario_id' => 'registrosanitario',
            'presentacione_id' => 'presentacion',
            'tipo_producto_id' => 'tipo de producto'// tipp de producto
        ];
    }

    public function messages()
    {
        return [
            'codigo.required'=> 'Se necesita un campo codigo'
        ];
    }
}
