<?php

// app/Models/Etapa8.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa8 extends Model
{
    use HasFactory;

    protected $table = 'etapa8';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'data',        // JSON con la información de la etapa
    ];

    // Relación al Proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Relación al Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
