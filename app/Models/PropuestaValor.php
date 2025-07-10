<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropuestaValor extends Model
{
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'propuestavalor',
        'caracteristicas',
        'imagenes',
    ];
}
