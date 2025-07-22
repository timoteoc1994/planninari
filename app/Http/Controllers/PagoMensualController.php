<?php

namespace App\Http\Controllers;

use App\Models\CostoFijo;
use App\Models\CostoVariable;
use App\Models\Empleado;
use App\Models\PagoMensual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PagoMensualController extends Controller
{
    public function index($proyectoId)
    {
        $userId = Auth::id();

        // Todos los pagos de deudas
        $pagos = PagoMensual::where('proyecto_id', $proyectoId)
                  ->where('user_id', $userId)
                  ->get();

        // Suma de costos fijos
        $totalFijos = CostoFijo::where('proyecto_id', $proyectoId)
                        ->where('user_id', $userId)
                        ->sum('valor');

        // Suma de gastos variables (total_costo)
        $totalVariables = CostoVariable::where('proyecto_id', $proyectoId)
                          ->where('user_id', $userId)
                          ->sum('total_costo');

        // Suma de todos los salarios (reales)
        $totalSalarios = Empleado::where('proyecto_id', $proyectoId)
                         ->where('user_id', $userId)
                         ->sum('salario_real');

        return Inertia::render('Etapa8/PagosMensuales', [
            'proyecto_id'    => $proyectoId,
            'pagos'          => $pagos,
            'totalFijos'     => $totalFijos,
            'totalVariables' => $totalVariables,
            'totalSalarios'  => $totalSalarios,
        ]);
    }

    public function store(Request $request, $proyectoId)
    {
        $data = $request->validate([
            'concepto' => 'required|string',
            'valor'    => 'required|numeric',
        ]);

        PagoMensual::create([
            'proyecto_id' => $proyectoId,
            'user_id'     => Auth::id(),
            'concepto'    => $data['concepto'],
            'valor'       => $data['valor'],
        ]);

        return redirect()->route('pagos-mensuales.index', ['id' => $proyectoId]);
    }

    public function update(Request $request, $proyectoId, PagoMensual $pago_mensual)
    {
        $data = $request->validate([
            'concepto' => 'required|string',
            'valor'    => 'required|numeric',
        ]);

        $pago_mensual->update([
            'concepto' => $data['concepto'],
            'valor'    => $data['valor'],
        ]);

        return redirect()->route('pagos-mensuales.index', ['id' => $proyectoId]);
    }

    public function destroy($proyectoId, PagoMensual $pago_mensual)
    {
        $pago_mensual->delete();

        return redirect()->route('pagos-mensuales.index', ['id' => $proyectoId]);
    }
}
