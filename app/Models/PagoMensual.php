<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMensual extends Model
{
    use HasFactory;

    protected $table = 'pagos_mensuales';

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
