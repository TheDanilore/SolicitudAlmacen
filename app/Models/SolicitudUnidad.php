<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudUnidad extends Model
{
    use HasFactory,Auditable;

    protected $table = 'solicitud_unidad';

    protected $fillable = ['estado', 'unidad_id','dependencia_id', 'tipo_requerimiento_id'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'solicitud_unidad_producto')->withPivot('cantidad');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class,'unidad_id');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class,'dependencia_id');
    }

    public function tipoRequerimiento(){
        return $this->belongsTo(TipoRequerimiento::class,'tipo_requerimiento_id');
    }


}
