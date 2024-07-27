<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory,Auditable;
    protected $table ='unidad_medida';
    
    protected $fillable = [
        'descripcion',
        'abreviacion',
    ];


}
