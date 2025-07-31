<?php

namespace App\Http\Controllers;

use App\Models\PresupuestoMarketing;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PresupuestoMarketingController extends Controller
{
    // Mostrar el presupuesto y ROI para un proyecto
    public function index($proyectoId)
    {
        $userId = Auth::id();

        $presupuestoMarketing = PresupuestoMarketing::firstOrCreate(
            [
                'proyecto_id' => $proyectoId,
                'user_id'     => $userId,
            ],
            [
                'medios' => [],
                'costos_trimestrales' => array_fill(0, 8, 0), // Total de costos por trimestre
                'ganancias_trimestrales' => array_fill(0, 8, 0), // Total de ganancias por trimestre
                'roi_dolares' => array_fill(0, 8, 0),
                'roi_porcentajes' => array_fill(0, 8, 0),
            ]
        );

        return Inertia::render('Etapa8/PresupuestoMarketingIndex', [
            'presupuestoMarketing' => $presupuestoMarketing,
            'proyecto_id' => $proyectoId,
        ]);
    }

    // Guardar o actualizar el presupuesto de marketing
    public function store(Request $request, $proyectoId)
    {
        $validated = $request->validate([
            'medios' => 'nullable|array',
            'medios.*.nombre' => 'required|string|max:255',
            'medios.*.valores' => 'required|array|size:8',
            'medios.*.valores.*' => 'nullable|numeric|min:0',
            'ganancias_trimestrales' => 'nullable|array|size:8',
            'ganancias_trimestrales.*' => 'nullable|numeric|min:0',
        ]);

        $userId = Auth::id();

        $presupuestoMarketing = PresupuestoMarketing::updateOrCreate(
            [
                'proyecto_id' => $proyectoId,
                'user_id' => $userId,
            ],
            [
                'medios' => $validated['medios'] ?? [],
                'ganancias_trimestrales' => $validated['ganancias_trimestrales'] ?? array_fill(0, 8, 0),
            ]
        );

        // Calcular los totales y el ROI
        $presupuestoMarketing->calcularTotales();

        return redirect()->route('presupuesto-marketing.index', $proyectoId)
            ->with('success', 'Presupuesto guardado exitosamente');
    }

    // Método para actualizar (PUT/PATCH)
    public function update(Request $request, $proyectoId)
    {
        return $this->store($request, $proyectoId);
    }
}
