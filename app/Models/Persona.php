<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    // Relación con la tabla documentos
    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    // Relación con la tabla proveedores
    public function proveedore()
    {
        return $this->hasOne(Proveedore::class);
    }

    // Relación con la tabla clientes
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    // Relación con la tabla razones_sociales (una persona puede tener varias razones sociales)
    public function razonesSociales()
    {
        return $this->hasMany(RazonSocial::class);
    }

    // Campos que se pueden rellenar en el modelo
    protected $fillable = [
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'tipo_persona',
        'documento_id',
        'numero_documento',
        'telefono', // campo añadido
        'correo_electronico' // campo añadido
    ];
}
