<?php

namespace App\Http\Controllers;

use App\Models\Integrantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class IntegrantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {

        $user = auth()->user();
        $etapa2 = Integrantes::where('proyecto_id', $id)->where('user_id', $user->id)->get();
        return Inertia::render('Etapa2', [
            'etapa2' => $etapa2,
            'proyecto_id' => $id,
        ]);
    }

    public function create($proyecto_id)
    {
        return Inertia::render('IntegranteForm', [
            'proyecto_id' => $proyecto_id,
            'integrante' => null,
        ]);
    }

    public function edit(Integrantes $integrante)
    {
        return Inertia::render('IntegranteForm', [
            'proyecto_id' => $integrante->proyecto_id,
            'integrante' => $integrante,
        ]);
    }

        public function store(Request $request, $proyecto_id)
        {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'nacionalidad' => 'nullable|string|max:255',
                'pais_residencia' => 'nullable|string|max:255',
                'ciudad_residencia' => 'nullable|string|max:255',
                'sector' => 'nullable|string|max:255',
                'tipo_identificacion' => 'required|string|max:50',
                'numero_identificacion' => 'required|string|max:100',
                'fecha_nacimiento' => 'nullable|date',
                'email' => 'nullable|email|max:255',
                'telefono' => 'nullable|string|max:50',
                'discapcidad' => 'nullable|string|max:255',
                'dispone_cuenta' => 'nullable|string|max:10',
            ]);
            $data['user_id'] = auth()->id();
            $data['proyecto_id'] = $proyecto_id;

            Integrantes::create($data);

            return redirect()->back()->with('success', 'Integrante creado correctamente');
        }

        public function update(Request $request, Integrantes $integrante)
        {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'nacionalidad' => 'nullable|string|max:255',
                'pais_residencia' => 'nullable|string|max:255',
                'ciudad_residencia' => 'nullable|string|max:255',
                'sector' => 'nullable|string|max:255',
                'tipo_identificacion' => 'required|string|max:50',
                'numero_identificacion' => 'required|string|max:100',
                'fecha_nacimiento' => 'nullable|date',
                'email' => 'nullable|email|max:255',
                'telefono' => 'nullable|string|max:50',
                'discapcidad' => 'nullable|string|max:255',
                'dispone_cuenta' => 'nullable|string|max:10',
            ]);
            $integrante->update($data);

            return redirect()->back()->with('success', 'Integrante actualizado correctamente');
        }

        public function destroy(Integrantes $integrante)
        {
            $integrante->delete();
            return redirect()->back()->with('success', 'Integrante eliminado correctamente');
        }
}
