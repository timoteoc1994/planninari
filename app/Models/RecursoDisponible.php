<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecursoDisponible extends Model
{
    use HasFactory;

    // Fuerza a usar la tabla 'recursos_disponibles'
    protected $table = 'recursos_disponibles';

    protected $fillable = [
        'recursos_clave_id',
        'tipo_recurso',
        'descripcion',
        'cantidad',
        'estado',
        'importancia',
        'descripcion_importancia',
    ];

    public function clave()
    {
        return $this->belongsTo(RecursosClave::class, 'recursos_clave_id');
    }
}
