<?php

// app/Models/PuntoEquilibrio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoEquilibrio extends Model
{
    use HasFactory;

    protected $table = 'punto_equilibrios';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'dias_por_semana',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
