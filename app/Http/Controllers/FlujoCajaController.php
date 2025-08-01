<?php

namespace App\Http\Controllers;

use App\Models\FlujoCaja;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FlujoCajaController extends Controller
{
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
                'tna_inversion_alternativa' => 0,
                'tir_anual'                 => 0,
                'van'                       => 0,
            ]
        );

        return Inertia::render('Etapa8/FlujoCajaMensual', [
            'proyecto_id'               => $proyectoId,
            'flujo_id'                  => $flujo->id,
            'saldoInicial'             => $flujo->saldo_inicial,
            'ingresos'                 => $flujo->ingresos,
            'gastos'                   => $flujo->gastos,
            'financiamientoRecibido'  => $flujo->financiamiento_recibido,
            'pagosFinanciamiento'     => $flujo->pagos_financiamiento,
            'tna_inversion_alternativa' => $flujo->tna_inversion_alternativa,
            'tir_anual'                => $flujo->tir_anual,
            'van'                      => $flujo->van,
        ]);
    }

    public function analisis($proyectoId)
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
                'tna_inversion_alternativa' => 0,
                'tir_anual'                 => 0,
                'van'                       => 0,
            ]
        );

        $proyecto = Project::find($proyectoId);

        return Inertia::render('Etapa8/AnalisisFinanciero', [
            'flujo_id'                  => $flujo->id,
            'proyecto_id'               => $proyectoId,
            'saldo_inicial'             => $flujo->saldo_inicial,
            'ingresos'                  => $flujo->ingresos,
            'gastos'                    => $flujo->gastos,
            'tna_inversion_alternativa' => $flujo->tna_inversion_alternativa,
            'tir_anual'                 => $flujo->tir_anual,
            'van'                       => $flujo->van,
            'proyecto_nombre'           => $proyecto?->name ?? 'Proyecto sin nombre',
        ]);
    }

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
                'tna_inversion_alternativa' => 0,
                'tir_anual'                 => 0,
                'van'                       => 0,
            ]
        );

        $validated = $request->validate([
            'saldo_inicial'             => 'nullable|numeric',
            'ingresos'                  => 'nullable|array',
            'gastos'                    => 'nullable|array',
            'financiamiento_recibido'  => 'nullable|array',
            'pagos_financiamiento'     => 'nullable|array',
            'tna_inversion_alternativa'=> 'nullable|numeric',
            'tir_anual'                 => 'nullable|numeric',
            'van'                       => 'nullable|numeric',
        ]);

        foreach ($validated as $campo => $valor) {
            $flujo->{$campo} = $valor;
        }

        $flujo->save();

        return $this->index($proyectoId);
    }
}
