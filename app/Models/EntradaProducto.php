<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaProducto extends Model
{
    use HasFactory, Auditable;
    
    protected $table = 'entrada_producto';

    protected $fillable = [
        'proveedor_id',
        'guia_remision',
        'tipo_entrada',
        'procedencia',
    ];


    public function itemsEntrada()
    {
        return $this->hasMany(ItemEntrada::class);
    }
    public function provedorr()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }


}
