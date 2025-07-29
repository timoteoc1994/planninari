<?php

namespace App\Http\Controllers;

use App\Models\etapa3;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class Etapa3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($proyecto_id)
    {
        $user = Auth::user();
        $etapa3 = etapa3::where('proyecto_id', $proyecto_id)->where('user_id', $user->id)->first();
        $proyectoactual= Project::find($proyecto_id);
        return inertia('Etapa3', [
            'etapa3' => $etapa3,
            'proyecto_id' => $proyecto_id,
            'proyectoactual' => $proyectoactual
        ]);
    }

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'problema' => 'required|string|max:1000',
            'solucion' => 'required|string|max:1000',
            'proyecto_id' => 'required|integer',
        ]);
        $validated['user_id'] = Auth::id();

        etapa3::create($validated);

        return redirect()->back()->with('success', 'Datos guardados correctamente');
    }

    public function update(Request $request, etapa3 $etapa3)
    {
  
        $validated = $request->validate([
            'problema' => 'required|string|max:1000',
            'solucion' => 'required|string|max:1000',
            'proyecto_id' => 'required|integer',
        ]);
        $validated['user_id'] = Auth::id();

        $etapa3->update($validated);

        return redirect()->back()->with('success', 'Datos actualizados correctamente');
    }
}
