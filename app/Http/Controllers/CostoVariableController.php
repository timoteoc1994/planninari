<?php

namespace App\Http\Controllers;

use App\Models\CostoVariable;
use App\Models\RecetaEstandar;
use App\Models\MaterialInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CostoVariableController extends Controller
{
    public function index($proyectoId)
    {
        $userId   = Auth::id();
        $recetas  = RecetaEstandar::where('proyecto_id', $proyectoId)
                     ->where('user_id', $userId)
                     ->get(['ref','nombre_producto','presentacion','precio_venta']);
        $registros = CostoVariable::where('proyecto_id', $proyectoId)
                      ->where('user_id', $userId)
                      ->get();

        // Suma de material_insumos por ref
        $costosInsumos = MaterialInsumo::select('ref', DB::raw('SUM(valor_usd) as costo_unitario'))
            ->where('proyecto_id', $proyectoId)
            ->where('user_id', $userId)
            ->groupBy('ref')
            ->pluck('costo_unitario', 'ref');

        return Inertia::render('Etapa8/CostoVariable', [
            'proyecto_id'   => $proyectoId,
            'recetas'       => $recetas,
            'registros'     => $registros,
            'costosInsumos' => $costosInsumos,
        ]);
    }

    public function store(Request $request, $proyectoId)
    {
        $data = $request->validate([
            'ref'           => 'required|string',
            'unidad'        => 'nullable|string',
            'unidades_mes'  => 'required|numeric',
        ]);

        // Precio de venta unitario
        $receta    = RecetaEstandar::where('proyecto_id', $proyectoId)
                       ->where('user_id', Auth::id())
                       ->where('ref', $data['ref'])
                       ->firstOrFail();
        $ventaUnit = $receta->precio_venta;

        // Costo unitario = suma de valor_usd en material_insumos
        $costoUnit = MaterialInsumo::where('proyecto_id', $proyectoId)
                       ->where('user_id', Auth::id())
                       ->where('ref', $data['ref'])
                       ->sum('valor_usd');

        // Totales y porcentajes
        $totalCosto = $data['unidades_mes'] * $costoUnit;
        $totalVenta = $data['unidades_mes'] * $ventaUnit;
        $pctCosto   = $totalVenta ? round(($totalCosto / $totalVenta) * 100) : 0;
        $pctMargen  = 100 - $pctCosto;

        $pctCosto  = max(0, min(100, $pctCosto));
        $pctMargen = max(0, min(100, $pctMargen));

        CostoVariable::create([
            'proyecto_id'       => $proyectoId,
            'user_id'           => Auth::id(),
            'ref'               => $data['ref'],
            'unidad'            => $data['unidad'] ?? null,
            'unidades_mes'      => $data['unidades_mes'],
            'costo_unitario'    => $costoUnit,
            'venta_unitario'    => $ventaUnit,
            'total_costo'       => $totalCosto,
            'total_venta'       => $totalVenta,
            'pct_costo_venta'   => $pctCosto,
            'pct_margen'        => $pctMargen,
            'pct_participacion' => 0,
        ]);

        return redirect()->route('costovariable.index', ['id' => $proyectoId]);
    }

public function update(Request $request, $proyectoId, CostoVariable $registro)
{
    $data = $request->validate([
        'ref'           => 'required|string',
        'unidad'        => 'nullable|string',
        'unidades_mes'  => 'required|numeric',
    ]);

    $receta    = RecetaEstandar::where('proyecto_id', $proyectoId)
                   ->where('user_id', Auth::id())
                   ->where('ref', $data['ref'])
                   ->firstOrFail();

    $ventaUnit = $receta->precio_venta;

    $costoUnit = MaterialInsumo::where('proyecto_id', $proyectoId)
                   ->where('user_id', Auth::id())
                   ->where('ref', $data['ref'])
                   ->sum('valor_usd');

    $totalCosto = $data['unidades_mes'] * $costoUnit;
    $totalVenta = $data['unidades_mes'] * $ventaUnit;

    // Cálculo con límites seguros
    $pctCosto = $totalVenta > 0 ? round(($totalCosto / $totalVenta) * 100) : 0;
    $pctCosto = max(0, min(100, $pctCosto));

    $pctMargen = 100 - $pctCosto;
    $pctMargen = max(0, min(100, $pctMargen));

    $registro->update([
        'ref'               => $data['ref'],
        'unidad'            => $data['unidad'] ?? null,
        'unidades_mes'      => $data['unidades_mes'],
        'costo_unitario'    => $costoUnit,
        'venta_unitario'    => $ventaUnit,
        'total_costo'       => $totalCosto,
        'total_venta'       => $totalVenta,
        'pct_costo_venta'   => $pctCosto,
        'pct_margen'        => $pctMargen,
    ]);

    return redirect()->route('costovariable.index', ['id' => $proyectoId]);
}

    public function destroy($proyectoId, CostoVariable $registro)
    {
        $registro->delete();
        return redirect()->route('costovariable.index', ['id' => $proyectoId]);
    }
}
