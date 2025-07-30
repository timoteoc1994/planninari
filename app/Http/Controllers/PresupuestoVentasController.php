<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\PuntoEquilibrio;
use App\Models\RecetaEstandar;
use App\Models\CostoVariable;
use App\Models\CostoFijo;
use App\Models\Empleado;
use App\Models\MetaVenta;

class PresupuestoVentasController extends Controller
{
    /**
     * Muestra la vista de Presupuesto Mensual de Ventas y Proyecciones,
     * junto con las metas 100% y 125% cargadas desde la base de datos.
     */
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

        // 4) Enriquecer recetas con datos de costo, precio, margen y salarios
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

        // 8) Cargar metas existentes desde la base de datos
        $metas = MetaVenta::where('proyecto_id', $proyectoId)
                    ->where('user_id', $userId)
                    ->get(['ref', 'meta_100', 'meta_125']);

        // 9) Renderizar la vista con Inertia
        return Inertia::render('Etapa8/PresupuestoVentas', [
            'proyecto_id'                     => $proyectoId,
            'recetas'                         => $productos,
            'dias_por_semana'                 => $config->dias_por_semana,
            'punto_equilibrio_mensual_global' => (int) round($unidadesMensualesBEGlobal),
            'totalSalariosReales'             => $totalSalariosReales,
            'totalMargenPonderado'            => $totalMargenPonderado,
            'totalCostos'                     => $totalCostos,
            'metas'                           => $metas,
        ]);
    }

    /**
     * Guarda o actualiza las metas (100% y 125%) enviadas desde el front.
     *
     * POST /api/proyectos/{proyecto}/metas-ventas
     */
    public function storeMetas(Request $request, $proyectoId)
    {
        $userId = Auth::id();

        $payload = $request->validate([
            'metas'             => 'required|array',
            'metas.*.meta100'   => 'required|integer|min:0',
            'metas.*.meta125'   => 'required|integer|min:0',
        ]);

        foreach ($payload['metas'] as $ref => $vals) {
            MetaVenta::updateOrCreate(
                [
                    'proyecto_id' => $proyectoId,
                    'user_id'     => $userId,
                    'ref'         => $ref,
                ],
                [
                    'meta_100'    => $vals['meta100'],
                    'meta_125'    => $vals['meta125'],
                ]
            );
        }

        return response()->json(['message' => 'Metas guardadas']);
    }

    /**
     * Elimina todas las metas asociadas al proyecto y usuario.
     *
     * DELETE /api/proyectos/{proyecto}/metas-ventas
     */
    public function clearMetas($proyectoId)
    {
        $userId = Auth::id();

        MetaVenta::where('proyecto_id', $proyectoId)
                 ->where('user_id', $userId)
                 ->delete();

        return response()->json(['message' => 'Metas eliminadas']);
    }
}
