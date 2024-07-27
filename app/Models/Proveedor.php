<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EstadoProveedor;
use App\Traits\Auditable;

class Proveedor extends Model
{
    use HasFactory,Auditable;

    protected $table = 'proveedor';

    protected $fillable = [
        'ruc', 
        'razon_social', 
        'direccion', 
        'prefijo_telefono',
        'telefono', 
        'estado_proveedor_id',
    ];

    public function estado()
    {
        return $this->belongsTo(EstadoProveedor::class, 'estado_proveedor_id');
    }
}
