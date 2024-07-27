<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaProducto extends Model
{
    use HasFactory, Auditable;
    
    protected $table = 'salida_producto';

    protected $fillable = [
    ];

    public function itemsSalida()
    {
        return $this->hasMany(ItemSalida::class);
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }


}
