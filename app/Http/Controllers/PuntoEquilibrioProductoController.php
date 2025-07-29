<?php

namespace App\Http\Controllers;

use App\Models\PuntoEquilibrio;
use App\Models\RecetaEstandar;
use App\Models\CostoVariable;
use App\Models\CostoFijo;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PuntoEquilibrioProductoController extends Controller
{
    public function index($proyectoId)
    {
        $userId = Auth::id();

        // 1) Configuración días x semana
        $config = PuntoEquilibrio::firstOrCreate(
            ['proyecto_id' => $proyectoId, 'user_id' => $userId],
            ['dias_por_semana' => 6]
        );

        // 2) Obtener recetas y costos
        $recetas = RecetaEstandar::where('proyecto_id', $proyectoId)
                     ->where('user_id', $userId)
                     ->get(['ref','nombre_producto','presentacion']);

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

        // 4) Combinar recetas con cálculos de margen y participación
        $productos = $recetas->map(function ($receta) use ($costosVariables, $config) {
            $c = $costosVariables->firstWhere('ref', $receta->ref);
            if (!$c) {
                // datos por defecto si no hay costo variable
                $receta->precio_venta = 0;
                $receta->costo_unitario = 0;
                $receta->unidades_diarias = 0;
                $receta->unidades_semanales = 0;
                $receta->unidades_mensuales = 0;
                $receta->margen_contribucion_unidad = 0;
                $receta->pct_participacion = 0;
                $receta->pct_margen = 0;
                $receta->margen_ponderado = 0;
                $receta->unidad_medida = 'unidad';
            } else {
                $pv = $c->venta_unitario;
                $cu = $c->costo_unitario;
                $receta->precio_venta = $pv;
                $receta->costo_unitario = $cu;
                $receta->unidades_diarias = $receta->unidades_diarias ?? 0;
                $receta->unidades_semanales = $receta->unidades_diarias * $config->dias_por_semana;
                $receta->unidades_mensuales = round($receta->unidades_semanales * (52/12));
                $receta->margen_contribucion_unidad = $pv - $cu;
                // participación sobre total_venta se calcula más abajo
                $receta->pct_margen = $c->pct_margen;
                $receta->unidad_medida = $c->unidad ?? 'unidad';
            }
            return $receta;
        });

        // 5) Totales globales de venta y margen ponderado
        $totalVentas = $costosVariables->sum('total_venta');
        // recalculamos pct_participacion y margen_ponderado con el total global
        $productos = $productos->map(function ($receta) use ($costosVariables, $totalVentas) {
            $cv = $costosVariables->firstWhere('ref', $receta->ref);
            if ($cv && $totalVentas > 0) {
                $pctPart = ($cv->total_venta / $totalVentas) * 100;
                $receta->pct_participacion = $pctPart;
                // margen ponderado = %participación × margen x unidad
                $receta->margen_ponderado = $pctPart * $receta->margen_contribucion_unidad;
            } else {
                $receta->pct_participacion = 0;
                $receta->margen_ponderado = 0;
            }
            return $receta;
        });

        $totalMargenPonderado = $productos->sum('margen_ponderado');

        // 6) Unidades mensuales necesarias **global**
        $unidadesMensualesNecesariasGlobal = $totalMargenPonderado > 0
            ? $totalCostos / $totalMargenPonderado
            : 0;

        // 7) Ahora asignamos la unidad neces. individual a cada producto
        $productos = $productos->map(function ($receta) use ($unidadesMensualesNecesariasGlobal) {
    // Aplicamos directamente el cálculo sin dividir entre 100
    $receta->unidades_mensuales_necesarias = round(
        $unidadesMensualesNecesariasGlobal * $receta->pct_participacion,
        0  // Sin decimales
    );
    return $receta;
});
        return Inertia::render('Etapa8/PuntoEquilibrioProductos', [
            'proyecto_id' => $proyectoId,
            'recetas'    => $productos,
            'dias_por_semana'              => $config->dias_por_semana,
            'totalUnidadesMensuales'       => $productos->sum('unidades_mensuales'),
            'totalMargenPonderado'         => $totalMargenPonderado,
            'totalCostosFijos'             => $totalCostosFijos,
            'totalSalariosReales'          => $totalSalariosReales,
            'unidadesMensualesNecesarias'  => $unidadesMensualesNecesariasGlobal,
        ]);
    }
}
