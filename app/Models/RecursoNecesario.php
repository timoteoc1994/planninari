<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecursoNecesario extends Model
{
    use HasFactory;

    // Fuerza a usar la tabla 'recursos_necesarios'
    protected $table = 'recursos_necesarios';

    protected $fillable = [
        'recursos_clave_id',
        'tipo_recurso',
        'descripcion',
        'cantidad',
        'aporte_objetivo',
        'porcentaje',
        'precio',
        'puede_adquirir',
    ];

    public function clave()
    {
        return $this->belongsTo(RecursosClave::class, 'recursos_clave_id');
    }
}
