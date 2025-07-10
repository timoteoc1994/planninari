<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class etapa3 extends Model
{
    protected $table = 'etapa3s';

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'problema',
        'solucion',
    ];
}
