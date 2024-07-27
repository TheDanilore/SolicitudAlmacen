<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoDependencia extends Model
{
    use HasFactory;
    protected $table = 'estado_dependencia';

    protected $fillable = [
        'descripcion',
    ];

    public function dependencias()
    {
        return $this->hasMany(Dependencia::class);
    }
}



