<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory, Auditable;

    protected $table = 'personas';

    protected $fillable = [
        'primer_nombre', 'segundo_nombre', 'apellido_paterno',
        'apellido_materno', 'telefono', 'direccion',
        'tipo_documento_identidad_id', 'numero_documento','prefijo_telefono'
    ];

    public function tipoDocumentoIdentidad()
    {
        return $this->belongsTo(TipoDocumentoIdentidad::class, 'tipo_documento_identidad_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'persona_id');
    }
}
