<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSalida extends Model
{
    use HasFactory;
    protected $table = 'item_salida';

    protected $fillable = [
        'salida_producto_id',
        'productos_id',
        'cantidad',
        'p_unitario',
        'costo_total',
    ];

    public function salidaProducto()
    {
        return $this->belongsTo(SalidaProducto::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }

    public function movimiento()
    {
        return $this->hasOne(Movimiento::class, 'item_salida_id');
    }

}
