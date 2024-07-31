<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;

    public function categoria(){
        return $this->hasOne(Categoria::class);
    }
    public function registrosanitario(){
        return $this->hasOne(Registrosanitario::class);
    }
    public function presentacione(){
        return $this->hasOne(Presentacione::class);
    }

    protected $fillable = ['nombre','descripcion'];
}
