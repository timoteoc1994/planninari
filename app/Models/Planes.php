<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planes extends Model
{
    protected $table = 'planes';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
