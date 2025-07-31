<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefinicionEstrategica extends Model
{
    protected $table = 'definicion_estrategicas';
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'proposito_empresarial',
        'vision',
        'mision',
        'valores',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }
}
