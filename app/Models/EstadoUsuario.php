<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoUsuario extends Model
{
    use HasFactory;

    protected $table = 'estado_usuario';

    protected $fillable = ['descripcion'];

    public function users()
    {
        return $this->hasMany(User::class, 'estado_usuario_id');
    }
}
