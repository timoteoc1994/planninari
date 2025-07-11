<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadesClave extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividades_claves';
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'actividad_clave',
        'archivo'
    ];
}
