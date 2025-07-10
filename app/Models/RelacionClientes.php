<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelacionClientes extends Model
{
    protected $table = 'relacion_clientes';
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'relacion_clientes',
    ];
}
