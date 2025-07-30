<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlujoCaja extends Model
{
    protected $table = 'flujo_cajas';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'saldo_inicial',
        'ingresos',
        'gastos',
        'financiamiento_recibido',
        'pagos_financiamiento',
    ];

    protected $casts = [
        'saldo_inicial'            => 'decimal:2',
        'ingresos'                 => 'array',
        'gastos'                   => 'array',
        'financiamiento_recibido'  => 'array',
        'pagos_financiamiento'     => 'array',
    ];
}
