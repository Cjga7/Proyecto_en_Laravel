<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function proveedore()
    {
        return $this->hasOne(Proveedore::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    // Añadir los nuevos campos 'telefono' y 'correo_electronico'
    protected $fillable = [
        'razon_social',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'tipo_persona',
        'documento_id',
        'numero_documento',
        'telefono', // nuevo campo
        'correo_electronico' // nuevo campo
    ];
}
