<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa9 extends Model
{
    use HasFactory;

    // Los campos que se pueden llenar masivamente
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'video_pitch_path',
    ];

    /**
     * Usuario que sube el video
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Proyecto al que pertenece este pitch
     */
    public function proyecto()
    {
        // Asume que tu modelo de proyectos se llama Project
        return $this->belongsTo(Project::class, 'proyecto_id');
    }
}
