<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    use HasFactory,Auditable;

    protected $table = 'categoria_producto';

    protected $fillable = [
        'descripcion',
    ];
}
