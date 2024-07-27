<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentoIdentidad extends Model
{
    use HasFactory,Auditable;
    

    protected $table = 'tipo_documento_identidad';

    protected $fillable = ['descripcion'];

    public function personas()
    {
        return $this->hasMany(Persona::class, 'tipo_documento_identidad_id');
    }
}
