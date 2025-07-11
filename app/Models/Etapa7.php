<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecursoDisponible;
use App\Models\RecursoNecesario;

class Etapa7 extends Model
{
    use HasFactory;

    // Indica a Eloquent que use la tabla 'inversiones'
    protected $table = 'inversiones';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'tipo_origen',
        'recurso_id',
        'cantidad',
        'precio_unitario',
        'clasificacion',
        'vida_util',
    ];

    public function recurso()
    {
        return $this->tipo_origen === 'disponible'
            ? $this->belongsTo(RecursoDisponible::class, 'recurso_id')
            : $this->belongsTo(RecursoNecesario::class, 'recurso_id');
    }
}
