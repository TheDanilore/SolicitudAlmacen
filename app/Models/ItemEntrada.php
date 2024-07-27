<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEntrada extends Model
{
    use HasFactory;
    protected $table = 'item_entrada';
    protected $fillable = [
        'entrada_producto_id',
        'productos_id',
        'cantidad',
        'p_unitario',
        'igv',
        'costo_total',
    ];

    public function entradaProducto()
    {
        return $this->belongsTo(EntradaProducto::class,'entrada_producto_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }
    public function movimiento()
    {
        return $this->hasOne(Movimiento::class, 'item_entrada_id');
    }
}
