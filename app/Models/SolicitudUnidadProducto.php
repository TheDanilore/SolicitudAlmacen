<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudUnidadProducto extends Model
{
    use HasFactory;

    protected $table = 'solicitud_unidad_producto';

    protected $fillable = ['solicitud_unidad_id', 'producto_id'];

    public function producto()
    {
        return $this->belongsToMany(Producto::class);
    }

    public function solicitudUnidad()
    {
        return $this->belongsTo(SolicitudUnidad::class);
    }
}
