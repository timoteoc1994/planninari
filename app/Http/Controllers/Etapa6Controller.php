<?php

namespace App\Http\Controllers;

use App\Models\Etapa6;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class Etapa6Controller extends Controller
{
    public function index($proyecto_id)
    {
        $user   = Auth::user();
        $etapa6 = Etapa6::where('proyecto_id', $proyecto_id)
                        ->where('user_id', $user->id)
                        ->first();

        return Inertia::render('Etapa6', [
            'etapa6'      => $etapa6,
            'proyecto_id' => $proyecto_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mitigacion_dano_ambiental' => 'required|string|max:2000',
            'proyecto_id'               => 'required|integer|exists:projects,id',
        ]);

        $validated['user_id'] = Auth::id();

        Etapa6::create($validated);

        return back()->with('success', 'Texto de Mitigación del daño ambiental guardado correctamente.');
    }

    public function update(Request $request, Etapa6 $etapa6)
    {
        $validated = $request->validate([
            'mitigacion_dano_ambiental' => 'required|string|max:2000',
            'proyecto_id'               => 'required|integer|exists:projects,id',
        ]);

        $validated['user_id'] = Auth::id();

        $etapa6->update($validated);

        return back()->with('success', 'Texto de Mitigación del daño ambiental actualizado correctamente.');
    }
}
