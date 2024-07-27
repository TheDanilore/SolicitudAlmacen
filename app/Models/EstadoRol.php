<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoRol extends Model
{
    use HasFactory;

    protected $table = 'estado_rol';

    protected $fillable = ['descripcion'];

    public function roles()
    {
        return $this->hasMany(Rol::class, 'estado_rol_id');
    }
}
