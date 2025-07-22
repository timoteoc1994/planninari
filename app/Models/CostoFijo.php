<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostoFijo extends Model
{
    use HasFactory;

    protected $table = 'costos_fijos';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'concepto',
        'valor',
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
