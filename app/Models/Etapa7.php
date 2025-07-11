<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecursoDisponible;
use App\Models\RecursoNecesario;

class Etapa7 extends Model
{
    use HasFactory;

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

    // Relación con recursos disponibles
    public function recursoDisponible()
    {
        return $this->belongsTo(RecursoDisponible::class, 'recurso_id');
    }

    // Relación con recursos necesarios
    public function recursoNecesario()
    {
        return $this->belongsTo(RecursoNecesario::class, 'recurso_id');
    }

    // Relación virtual para acceder de forma uniforme desde Vue
    public function getRecursoAttribute()
    {
        return $this->tipo_origen === 'disponible'
            ? $this->recursoDisponible
            : $this->recursoNecesario;
    }

    // Hacer que la propiedad 'recurso' sea incluida al serializar
    protected $appends = ['recurso'];
}
