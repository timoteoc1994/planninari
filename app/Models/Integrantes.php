<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integrantes extends Model
{
    protected $table = 'integrantes';

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'nombre',
        'apellido',
        'nacionalidad',
        'pais_residencia',
        'ciudad_residencia',
        'sector',
        'tipo_identificacion',
        'numero_identificacion',
        'fecha_nacimiento',
        'email',
        'telefono',
        'discapcidad',
        'dispone_cuenta'
    ];
    

}
