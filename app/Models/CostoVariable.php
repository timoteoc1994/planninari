<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostoVariable extends Model
{
    use HasFactory;

    protected $table = 'costo_variables';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'ref',
        'unidad',
        'unidades_mes',
        'costo_unitario',
        'venta_unitario',
        'total_costo',
        'total_venta',
        'pct_costo_venta',
        'pct_margen',
        'pct_participacion',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receta()
    {
        return $this->belongsTo(RecetaEstandar::class, 'ref', 'ref');
    }
}
