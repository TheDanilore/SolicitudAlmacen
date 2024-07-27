<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory,Auditable;
    
    protected $table = 'dependencia';
    
    protected $fillable = [
        'nombre',
        'estado_id',
    ];
    
    public function estado()
    {
        return $this->belongsTo(EstadoDependencia::class, 'estado_id');
    }

    public function unidad()
    {
        return $this->hasMany(Unidad::class);
    }
}