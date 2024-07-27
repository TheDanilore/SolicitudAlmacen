<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory, Auditable;
    
    protected $table='unidad';

    protected $fillable = [
        'dependencia_id',
        'descripcion',
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class,'dependencia_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudUnidad::class);
    }

    public function solicitudDependencia()
    {
        return $this->hasMany(SolicitudDependencia::class);
    }
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
