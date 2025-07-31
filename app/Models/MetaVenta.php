<?php
// app/Models/MetaVenta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaVenta extends Model
{
        protected $table = 'metas_ventas';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'ref',
        'meta_100',
        'meta_125',
    ];
}
