<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planes';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
