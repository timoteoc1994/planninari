<?php

namespace App\Http\Controllers;

use App\Models\PuntoEquilibrio;
use App\Models\CostoFijo;
use App\Models\CostoVariable;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PuntoEquilibrioController extends Controller
{
    public function index($proyectoId)
    {
        $userId = Auth::id();

        // 1) Obtener o crear config sin valor por defecto
        $config = PuntoEquilibrio::firstOrCreate(
            ['proyecto_id' => $proyectoId, 'user_id' => $userId],
            ['dias_por_semana' => null]
        );

        // 2) Sumas básicas
        $totalSales  = CostoVariable::where('proyecto_id', $proyectoId)
                          ->where('user_id', $userId)
                          ->sum('total_venta');
        $totalCostDB = CostoVariable::where('proyecto_id', $proyectoId)
                          ->where('user_id', $userId)
                          ->sum('total_costo');

        // 3) Margen bruto
        $totalMargin = $totalSales - $totalCostDB;

        // 4) % Margen de contribución
        $marginPct = $totalSales
            ? round($totalMargin / $totalSales * 100, 2)
            : 0;

        // 5) Costo variable por fórmula (Ventas * (1 − %margen))
        $totalVariables = round($totalSales * (1 - $marginPct/100), 2);

        // 6) Costos fijos y salarios
        $totalFijos    = CostoFijo::where('proyecto_id', $proyectoId)
                          ->where('user_id', $userId)
                          ->sum('valor');
        $totalSalarios = Empleado::where('proyecto_id', $proyectoId)
                          ->where('user_id', $userId)
                          ->sum('salario_real');

        // 7) Punto de equilibrio mensual
        $breakEvenMonthly = $marginPct
            ? round(($totalFijos + $totalSalarios) / ($marginPct/100), 2)
            : 0;

        // 8) Punto diario (si hay días configurados)
        if ($config->dias_por_semana) {
            $weeksPerMonth    = 52/12;
            $workDaysPerMonth = $config->dias_por_semana * $weeksPerMonth;
            $breakEvenDaily   = $workDaysPerMonth
                ? round($breakEvenMonthly / $workDaysPerMonth, 2)
                : null;
        } else {
            $breakEvenDaily = null;
        }

        // 9) Punto semanal siempre = (mensual * 12) / 52
        $breakEvenWeekly = round($breakEvenMonthly * 12 / 52, 2);

        return Inertia::render('Etapa8/PuntoEquilibrio', [
            'proyecto_id'        => $proyectoId,
            'config'             => $config,
            'totalVariables'     => $totalVariables,
            'totalSales'         => $totalSales,
            'totalMargin'        => $totalMargin,
            'totalFijos'         => $totalFijos,
            'totalSalarios'      => $totalSalarios,
            'marginPct'          => $marginPct,
            'breakEvenMonthly'   => $breakEvenMonthly,
            'breakEvenDaily'     => $breakEvenDaily,
            'breakEvenWeekly'    => $breakEvenWeekly,
        ]);
    }

    public function update(Request $request, $proyectoId, PuntoEquilibrio $punto_equilibrio)
    {
        $data = $request->validate([
            'dias_por_semana' => 'required|integer|min:1|max:7',
        ]);
        $punto_equilibrio->update($data);

        return redirect()->route('punto-equilibrio.index', ['id' => $proyectoId]);
    }
}
