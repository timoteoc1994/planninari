<?php

namespace App\Http\Controllers;

use App\Models\PuntoEquilibrio;
use App\Models\RecetaEstandar;
use App\Models\CostoVariable;
use App\Models\CostoFijo;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PuntoEquilibrioProductoController extends Controller
{
    public function index($proyectoId)
    {
        // 1) Configuración días por semana
        $userId = Auth::id();
        $config = PuntoEquilibrio::firstOrCreate(
            ['proyecto_id' => $proyectoId, 'user_id' => $userId],
            ['dias_por_semana' => 6]
        );

        // 2) Cargar recetas y costos variables
        $recetas = RecetaEstandar::where('proyecto_id', $proyectoId)
                     ->where('user_id', $userId)
                     ->get(['ref', 'nombre_producto', 'presentacion']);
        $costosVariables = CostoVariable::where('proyecto_id', $proyectoId)
                                        ->where('user_id', $userId)
                                        ->get();

        // 3) Calcular totales de salarios y costos fijos
        $totalSalariosReales = Empleado::where('proyecto_id', $proyectoId)
                                      ->where('user_id', $userId)
                                      ->sum('salario_real');
        $totalCostosFijos = CostoFijo::where('proyecto_id', $proyectoId)
                                     ->where('user_id', $userId)
                                     ->sum('valor');
        $totalCostos = $totalSalariosReales + $totalCostosFijos;

        // 4) Enriquecer cada receta con datos de costo y margen
        $productos = $recetas->map(function ($receta) use ($costosVariables) {
            $cv = $costosVariables->firstWhere('ref', $receta->ref);
            if ($cv) {
                $receta->precio_venta = $cv->venta_unitario;
                $receta->costo_unitario = $cv->costo_unitario;
                $receta->margen_contribucion_unidad = $cv->venta_unitario - $cv->costo_unitario;
                $receta->pct_margen = $cv->pct_margen;
                $receta->unidad_medida = $cv->unidad ?? 'unidad';
            } else {
                $receta->precio_venta = 0;
                $receta->costo_unitario = 0;
                $receta->margen_contribucion_unidad = 0;
                $receta->pct_margen = 0;
                $receta->unidad_medida = 'unidad';
            }
            return $receta;
        });

        // 5) % participación y margen ponderado
        $totalVentas = $costosVariables->sum('total_venta');
        $productos = $productos->map(function ($receta) use ($costosVariables, $totalVentas) {
            $cv = $costosVariables->firstWhere('ref', $receta->ref);
            if ($cv && $totalVentas > 0) {
                $receta->pct_participacion = ($cv->total_venta / $totalVentas) * 100;
                $receta->margen_ponderado = $receta->pct_participacion * $receta->margen_contribucion_unidad;
            } else {
                $receta->pct_participacion = 0;
                $receta->margen_ponderado = 0;
            }
            return $receta;
        });

        // 6) Total margen ponderado y unidades mensuales globales
        $totalMargenPonderado = $productos->sum('margen_ponderado');
        $unidadesMensualesNecesariasGlobal = $totalMargenPonderado > 0
            ? $totalCostos / $totalMargenPonderado
            : 0;

        // 7) **Cálculo de unidades**: mensuales, semanales y diarias
        $productos = $productos->map(function ($receta) use ($unidadesMensualesNecesariasGlobal, $config) {
            // Unidades mensuales necesarias por producto
            $receta->unidades_mensuales_necesarias = (int) round(
                $unidadesMensualesNecesariasGlobal * $receta->pct_participacion,
                0
            );

            // Unidades semanales ≈ (mensuales × 12) / 52
            $receta->unidades_semanales = (int) round(
                $receta->unidades_mensuales_necesarias * 12 / 52,
                0
            );

            // **Unidades diarias** usando tu fórmula: ((mensuales × 12) / 52) / diasPorSemana
            $receta->unidades_diarias = $config->dias_por_semana > 0
                ? (int) round(
                    ($receta->unidades_mensuales_necesarias * 12 / 52)
                    / $config->dias_por_semana,
                    0
                )
                : 0;

            return $receta;
        });

        // 8) Enviar todo a la vista
        return Inertia::render('Etapa8/PuntoEquilibrioProductos', [
            'proyecto_id'                 => $proyectoId,
            'recetas'                     => $productos,
            'dias_por_semana'             => $config->dias_por_semana,
            'totalUnidadesMensuales'      => $productos->sum('unidades_mensuales_necesarias'),
            'totalMargenPonderado'        => $totalMargenPonderado,
            'totalCostosFijos'            => $totalCostosFijos,
            'totalSalariosReales'         => $totalSalariosReales,
            'unidadesMensualesNecesarias' => $unidadesMensualesNecesariasGlobal,
        ]);
    }
}
