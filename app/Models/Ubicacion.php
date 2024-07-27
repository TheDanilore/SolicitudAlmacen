<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory, Auditable;

    protected $table = 'ubicacion';

    protected $fillable = ['code', 'descripcion'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
