<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    use HasFactory;

    protected $table = 'tipos_productos';

    public function producto()
    {
        return $this->hasMany(Producto::class, 'tipo_producto_id');
    }
}
