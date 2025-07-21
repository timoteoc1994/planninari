<?php

namespace App\Observers;

use App\Models\MaterialInsumo;
use App\Models\CostoVariable;
use Illuminate\Support\Facades\DB;

class MaterialInsumoObserver
{
    /**
     * Se dispara tras crear o actualizar un insumo.
     */
    public function saved(MaterialInsumo $insumo)
    {
        $this->recalculateCostoVariable($insumo);
    }

    /**
     * Se dispara tras borrar un insumo.
     */
    public function deleted(MaterialInsumo $insumo)
    {
        $this->recalculateCostoVariable($insumo);
    }

    /**
     * Recalcula todos los registros de costo_variable
     * para el mismo proyecto, usuario y ref.
     */
    protected function recalculateCostoVariable(MaterialInsumo $insumo)
    {
        $proyectoId = $insumo->proyecto_id;
        $userId     = $insumo->user_id;
        $ref        = $insumo->ref;

        // Nuevo costo unitario = suma de valor_usd
        $nuevoCostoUnit = MaterialInsumo::where('proyecto_id', $proyectoId)
            ->where('user_id', $userId)
            ->where('ref', $ref)
            ->sum('valor_usd');

        // Trae todos los registros afectados
        $variables = CostoVariable::where('proyecto_id', $proyectoId)
            ->where('user_id', $userId)
            ->where('ref', $ref)
            ->get();

        foreach ($variables as $var) {
            $totalCosto = $var->unidades_mes * $nuevoCostoUnit;
            $totalVenta = $var->unidades_mes * $var->venta_unitario;
            $pctCosto   = $totalVenta ? round(($totalCosto / $totalVenta) * 100) : 0;
            $pctMargen  = 100 - $pctCosto;

            // Clamp entre 0 y 100
            $pctCosto  = max(0, min(100, $pctCosto));
            $pctMargen = max(0, min(100, $pctMargen));

            $var->update([
                'costo_unitario'  => $nuevoCostoUnit,
                'total_costo'     => $totalCosto,
                'total_venta'     => $totalVenta,
                'pct_costo_venta' => $pctCosto,
                'pct_margen'      => $pctMargen,
            ]);
        }
    }
}
