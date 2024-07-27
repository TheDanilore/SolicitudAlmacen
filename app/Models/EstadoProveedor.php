<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoProveedor extends Model
{
    use HasFactory;

    protected $table = 'estado_proveedor';

    protected $fillable = [
        'descripcion',
    ];

}
