<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory, Auditable;
    protected $table = 'movimiento';

    protected $fillable = [
        'tipo',
        'producto_id',
        'item_entrada_id',
        'item_salida_id',
        'stock_anterior',
        'cantidad',
    ];


    public function itemEntrada()
    {
        return $this->belongsTo(ItemEntrada::class, 'item_entrada_id');
    }

    public function itemSalida()
    {
        return $this->belongsTo(ItemSalida::class, 'item_salida_id');
    }
    public function producto(){
        return $this->belongsTo(Producto::class, 'producto_id');
    }



}