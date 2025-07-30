<?php

namespace App\Http\Controllers;

use App\Models\FlujoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FlujoCajaController extends Controller
{
    /**
     * Muestra la vista con los datos existentes (o valores por defecto).
     */
    public function index($proyectoId)
    {
        $userId = Auth::id();

        $flujo = FlujoCaja::firstOrCreate(
            [
                'proyecto_id' => $proyectoId,
                'user_id'     => $userId,
            ],
            [
                'saldo_inicial'           => 0,
                'ingresos'                => [],
                'gastos'                  => [],
                'financiamiento_recibido' => [],
                'pagos_financiamiento'    => [],
            ]
        );

        return Inertia::render('Etapa8/FlujoCajaMensual', [
            'proyecto_id'            => $proyectoId,
            'flujo_id'               => $flujo->id,
            'saldoInicial'           => $flujo->saldo_inicial,
            'ingresos'               => $flujo->ingresos,
            'gastos'                 => $flujo->gastos,
            'financiamientoRecibido' => $flujo->financiamiento_recibido,
            'pagosFinanciamiento'    => $flujo->pagos_financiamiento,
        ]);
    }

    /**
     * Recibe un PATCH con cualquiera de los campos y actualiza el flujo.
     * Devuelve la misma vista Inertia con los datos recién guardados.
     */
    public function store(Request $request, $proyectoId)
    {
        $userId = Auth::id();

        $flujo = FlujoCaja::firstOrCreate(
            [
                'proyecto_id' => $proyectoId,
                'user_id'     => $userId,
            ],
            [
                'saldo_inicial'           => 0,
                'ingresos'                => [],
                'gastos'                  => [],
                'financiamiento_recibido' => [],
                'pagos_financiamiento'    => [],
            ]
        );

        $validated = $request->validate([
            'saldo_inicial'           => 'nullable|numeric',
            'ingresos'                => 'nullable|array',
            'gastos'                  => 'nullable|array',
            'financiamiento_recibido' => 'nullable|array',
            'pagos_financiamiento'    => 'nullable|array',
        ]);

        if ($request->has('saldo_inicial')) {
            $flujo->saldo_inicial = $validated['saldo_inicial'];
        }
        if ($request->has('ingresos')) {
            $flujo->ingresos = $validated['ingresos'];
        }
        if ($request->has('gastos')) {
            $flujo->gastos = $validated['gastos'];
        }
        if ($request->has('financiamiento_recibido')) {
            $flujo->financiamiento_recibido = $validated['financiamiento_recibido'];
        }
        if ($request->has('pagos_financiamiento')) {
            $flujo->pagos_financiamiento = $validated['pagos_financiamiento'];
        }

        $flujo->save();

        // <-- AQUÍ devolvemos Inertia en vez de JSON plano
        return $this->index($proyectoId);
    }
}
