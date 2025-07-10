<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa6 extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'mitigacion_dano_ambiental',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }
}
