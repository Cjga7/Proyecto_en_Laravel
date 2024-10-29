<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla
    protected $table = 'razon_social'; // Especifica el nombre correcto de la tabla

    // Definir la relación inversa con la tabla personas
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    // Campos que se pueden rellenar en el modelo
    protected $fillable = [
        'razon_social', // el nombre de la razón social
        'persona_id'    // la referencia a la persona (clave foránea)
    ];
}

