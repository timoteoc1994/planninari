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
        'tna_inversion_alternativa', // nuevo
        'tir_anual',                 // nuevo
        'van',                       // nuevo
    ];

    protected $casts = [
        'saldo_inicial'            => 'decimal:2',
        'ingresos'                 => 'array',
        'gastos'                   => 'array',
        'financiamiento_recibido' => 'array',
        'pagos_financiamiento'    => 'array',
        'tna_inversion_alternativa' => 'decimal:2', // nuevo
        'tir_anual'                 => 'decimal:2', // nuevo
        'van'                       => 'decimal:2', // nuevo
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Método para calcular los flujos netos (ingresos - gastos + financiamiento - pagos)
    public function calcularFlujosNetos()
    {
        $flujos = [];
        
        // Incluir el saldo inicial como un flujo negativo (inversión inicial)
        $flujos[] = -floatval($this->saldo_inicial);

        // Calcular flujos netos para cada mes (12 meses)
        for ($i = 0; $i < 12; $i++) {
            // Sumar los ingresos, gastos, financiamiento recibido y pagos de financiamiento de cada mes
            $ingresoMes = array_sum(array_column($this->ingresos, $i));  // Sumar ingresos del mes
            $gastoMes = array_sum(array_column($this->gastos, $i));  // Sumar gastos del mes
            $financiamientoMes = array_sum(array_column($this->financiamiento_recibido, $i));  // Sumar financiamiento recibido
            $pagoMes = array_sum(array_column($this->pagos_financiamiento, $i));  // Sumar pagos de financiamiento

            // Flujo neto para ese mes: ingresos + financiamiento - gastos - pagos
            $neto = $ingresoMes + $financiamientoMes - $gastoMes - $pagoMes;
            $flujos[] = round($neto, 2);  // Agregar el flujo neto del mes a la lista
        }

        return $flujos;  // Retorna todos los flujos netos
    }
}

