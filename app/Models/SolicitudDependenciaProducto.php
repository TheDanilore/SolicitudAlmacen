<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDependenciaProducto extends Model
{
    use HasFactory;

    protected $table = 'solicitud_dependencia_producto';

    protected $fillable = [
        'solicitud_dependencia_id',
        'producto_id',
        'cantidad',
    ];

    public function solicitudDependencia()
    {
        return $this->belongsTo(SolicitudDependencia::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
