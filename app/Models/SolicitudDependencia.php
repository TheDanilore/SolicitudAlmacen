<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDependencia extends Model
{
    use HasFactory, Auditable;

    protected $table = 'solicitud_dependencia';

    protected $fillable = [
        'solicitud_unidad_id',
        'unidad_id',
        'dependencia_id',
        'estado',
    ];

    public function solicitudUnidad()
    {
        return $this->belongsTo(SolicitudUnidad::class, 'solicitud_unidad_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
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
