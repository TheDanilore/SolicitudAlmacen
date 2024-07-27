<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoriaProducto;
use App\Models\UnidadMedida;
use App\Models\EstadoProducto;
use App\Traits\Auditable;

class Producto extends Model
{
    use HasFactory, Auditable;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_productos_id',
        'unidad_medida_id',
        'proveedor',
        'cantidad',
        'precio_actual',
        'ubicacion_id',
        'estado_producto_id',
        'imagen'
    ];


    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_productos_id');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoProducto::class, 'estado_producto_id');
    }


    //Proceso
    public function itemSalidas()
    {
        return $this->hasMany(ItemSalida::class);
    }

    public function itemEntradas()
    {
        return $this->hasMany(ItemEntrada::class);
    }

    //movimiento
    public  function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }


    //Ubicacion
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class,'ubicacion_id');
    }
}
