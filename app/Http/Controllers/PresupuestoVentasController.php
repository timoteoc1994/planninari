<?php

namespace App\Http\Controllers;

use App\Models\PuntoEquilibrio;
use App\Models\RecetaEstandar;
use App\Models\CostoVariable;
use App\Models\CostoFijo;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PresupuestoVentasController extends Controller
{
    public function index($proyectoId)
    {
        $userId = Auth::id();

        // 1) Días por semana
        $config = PuntoEquilibrio::firstOrCreate(
            ['proyecto_id' => $proyectoId, 'user_id' => $userId],
            ['dias_por_semana' => 6]
        );

        // 2) Recetas y costos variables
        $recetas = RecetaEstandar::where('proyecto_id', $proyectoId)
                    ->where('user_id', $userId)
                    ->get(['ref', 'nombre_producto', 'presentacion']);
        $costosVariables = CostoVariable::where('proyecto_id', $proyectoId)
                            ->where('user_id', $userId)
                            ->get();

        // 3) Totales de salarios y costos fijos
        $totalSalariosReales = Empleado::where('proyecto_id', $proyectoId)
                                      ->where('user_id', $userId)
                                      ->sum('salario_real');
        $totalCostosFijos = CostoFijo::where('proyecto_id', $proyectoId)
                                     ->where('user_id', $userId)
                                     ->sum('valor');
        $totalCostos = $totalSalariosReales + $totalCostosFijos;

        // 4) Enriquecer recetas con datos de costo, precio, margen y totalSalariosReales
        $productos = $recetas->map(function($r) use ($costosVariables, $totalSalariosReales) {
            $cv = $costosVariables->firstWhere('ref', $r->ref);

            $r->unidad_medida              = $cv->unidad ?? $r->presentacion ?? 'unidad';
            $r->precio_venta               = $cv->venta_unitario ?? 0;
            $r->costo_unitario             = $cv->costo_unitario ?? 0;
            $r->margen_contribucion_unidad = $r->precio_venta - $r->costo_unitario;
            $r->pct_margen                 = $cv->pct_margen ?? 0;
            $r->totalSalariosReales        = $totalSalariosReales;

            $r->pct_participacion             = 0;
            $r->margen_ponderado              = 0;
            $r->unidades_mensuales_necesarias = 0;

            return $r;
        });

        // 5) % participación y margen ponderado
        $totalVentas = $costosVariables->sum('total_venta');
        $productos = $productos->map(function($r) use ($costosVariables, $totalVentas) {
            $cv = $costosVariables->firstWhere('ref', $r->ref);
            if ($cv && $totalVentas > 0) {
                $r->pct_participacion = ($cv->total_venta / $totalVentas) * 100;
                $r->margen_ponderado  = $r->pct_participacion * $r->margen_contribucion_unidad;
            }
            return $r;
        });

        // 6) BE global en unidades mensuales
        $totalMargenPonderado = $productos->sum('margen_ponderado');
        $unidadesMensualesBEGlobal = $totalMargenPonderado > 0
            ? $totalCostos / $totalMargenPonderado
            : 0;

        // 7) Calcular unidades mensuales necesarias por producto
        $productos = $productos->map(function($r) use ($unidadesMensualesBEGlobal) {
            $r->unidades_mensuales_necesarias = (int) round(
                $unidadesMensualesBEGlobal * $r->pct_participacion,
                0
            );
            return $r;
        });

        // 8) Renderizar la vista con Inertia
        return Inertia::render('Etapa8/PresupuestoVentas', [
            'proyecto_id'                     => $proyectoId,
            'recetas'                         => $productos,
            'dias_por_semana'                 => $config->dias_por_semana,
            'punto_equilibrio_mensual_global' => (int) round($unidadesMensualesBEGlobal),
            'totalSalariosReales'             => $totalSalariosReales,
            'totalMargenPonderado'            => $totalMargenPonderado,
            'totalCostos'                     => $totalCostos,
        ]);
    }
}
