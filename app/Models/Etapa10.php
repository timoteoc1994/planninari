<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa10 extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'document_paths',
    ];

    // Relación al usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación al proyecto
    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }
}
