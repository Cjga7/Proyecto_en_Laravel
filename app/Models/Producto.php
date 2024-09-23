<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra');
    }

    public function ventas(){
        return $this->belongsToMany(Venta::class)->withTimestamps()->withPivot('cantidad','precio_venta','descuento');
    }
    public function categorias(){
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }
    public function registrosanitario(){
        return $this->belongsTo(Registrosanitario::class);
    }

    public function presentacione(){
        return $this->belongsTo(Presentacione::class);
    }
    public function tipoProducto()
{
    return $this->belongsTo(TipoProducto::class, 'tipo_producto_id');
}


    protected $fillable = ['codigo','nombre','descripcion','fecha_vencimiento','registrosanitario_id','presentacione_id','img_path','tipo_producto_id'];

    public function hanbleUploadImage($image){
        $file = $image;
        $name = time() .$file->getClientOriginalname();
        //$file->move(public_path().'/img/productos/',$name);
        Storage::putFileAs('/public/productos/',$file,$name,'public');
        return $name;
    }
}
