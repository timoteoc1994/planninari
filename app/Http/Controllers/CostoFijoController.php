<?php
// app/Http/Controllers/CostoFijoController.php

namespace App\Http\Controllers;

use App\Models\CostoFijo;
use App\Models\CostoVariable;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CostoFijoController extends Controller
{
    public function index($proyectoId)
    {
        $userId = Auth::id();

        // Costos fijos del proyecto
        $costosFijos = CostoFijo::where('proyecto_id', $proyectoId)
                        ->where('user_id', $userId)
                        ->get();

        // Suma de todos los costos variables (total_costo)
        $totalVariables = CostoVariable::where('proyecto_id', $proyectoId)
                          ->where('user_id', $userId)
                          ->sum('total_costo');

        // Suma de salarios reales de empleados y empresario
        $totalSalarios = Empleado::where('proyecto_id', $proyectoId)
                           ->where('user_id', $userId)
                           ->sum('salario_real');

        return Inertia::render('Etapa8/CostosFijos', [
            'proyecto_id'    => $proyectoId,
            'costosFijos'    => $costosFijos,
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

        CostoFijo::create([
            'proyecto_id' => $proyectoId,
            'user_id'     => Auth::id(),
            'concepto'    => $data['concepto'],
            'valor'       => $data['valor'],
        ]);

        return redirect()->route('costos-fijos.index', ['id' => $proyectoId]);
    }

    public function update(Request $request, $proyectoId, CostoFijo $costo_fijo)
    {
        $data = $request->validate([
            'concepto' => 'required|string',
            'valor'    => 'required|numeric',
        ]);

        $costo_fijo->update([
            'concepto' => $data['concepto'],
            'valor'    => $data['valor'],
        ]);

        return redirect()->route('costos-fijos.index', ['id' => $proyectoId]);
    }

    public function destroy($proyectoId, CostoFijo $costo_fijo)
    {
        $costo_fijo->delete();

        return redirect()->route('costos-fijos.index', ['id' => $proyectoId]);
    }
}
