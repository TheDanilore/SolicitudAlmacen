<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRequerimiento extends Model
{
    use HasFactory, Auditable;

    protected $table = 'tipo_requerimiento';

    protected $fillable = ['descripcion'];
}
