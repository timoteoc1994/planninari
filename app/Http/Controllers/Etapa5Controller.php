<?php

namespace App\Http\Controllers;

use App\Models\Etapa5;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class Etapa5Controller extends Controller
{
    public function index($proyecto_id)
    {
        $user   = Auth::user();
        $etapa5 = Etapa5::where('proyecto_id', $proyecto_id)
                        ->where('user_id', $user->id)
                        ->first();

        return Inertia::render('Etapa5', [
            'etapa5'      => $etapa5,
            'proyecto_id' => $proyecto_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'igualdad_genero' => 'required|string|max:2000',
            'proyecto_id'     => 'required|integer|exists:projects,id',
        ]);

        $validated['user_id'] = Auth::id();

        Etapa5::create($validated);

        return back()->with('success', 'Texto de Igualdad de Género guardado correctamente.');
    }

    public function update(Request $request, Etapa5 $etapa5)
    {
        $validated = $request->validate([
            'igualdad_genero' => 'required|string|max:2000',
            'proyecto_id'     => 'required|integer|exists:projects,id',
        ]);

        $validated['user_id'] = Auth::id();

        $etapa5->update($validated);

        return back()->with('success', 'Texto de Igualdad de Género actualizado correctamente.');
    }
}
