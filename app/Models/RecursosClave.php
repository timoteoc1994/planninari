<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecursosClave;           // ← Agrégalo
use App\Models\RecursoDisponible;       // ← Si usas CRUD de Disponibles
use App\Models\RecursoNecesario;        // ← Si usas CRUD de Necesarios
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecursosClave extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'proyecto_id',
        'user_id',
    ];

    /**
     * Relación a los recursos disponibles.
     */
    public function disponibles(): HasMany
    {
        return $this->hasMany(RecursoDisponible::class, 'recursos_clave_id');
    }

    /**
     * Relación a los recursos necesarios.
     */
    public function necesarios(): HasMany
    {
        return $this->hasMany(RecursoNecesario::class, 'recursos_clave_id');
    }
}
