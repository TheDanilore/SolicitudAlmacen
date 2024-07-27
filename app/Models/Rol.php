<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory, Auditable;
    protected $table = 'roles';


    protected $fillable = [
        'name',
        'guard_name',
        'estado_rol_id',
    ];
    public function estadoRol()
    {
        return $this->belongsTo(EstadoRol::class, 'estado_rol_id');
    }

}
