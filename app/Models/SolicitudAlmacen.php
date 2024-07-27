<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAlmacen extends Model
{
    use HasFactory, Auditable;

    protected $table = 'solicitud_almacen';

    protected $fillable = [
        'salida_producto_id',
        'solicitud_dependencia_id'
    ];

    public function salidaProducto()
    {
        return $this->belongsTo(SalidaProducto::class,'salida_producto_id');
    }

    public function salidaDependencia()
    {
        return $this->belongsTo(SolicitudDependencia::class,'solicitud_dependencia_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function productos()
    {
        return $this->hasMany(SolicitudDependenciaProducto::class);
    }



    public function itemsSalida()
    {
        return $this->hasMany(ItemSalida::class);
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }
}
