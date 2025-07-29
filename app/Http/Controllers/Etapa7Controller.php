<?php

namespace App\Http\Controllers;

use App\Models\Etapa7;
use App\Models\Project;
use App\Models\RecursosClave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class Etapa7Controller extends Controller
{
    // Mostrar formulario + listado de inversiones
    public function inversiones($proyecto_id)
    {
        $user = Auth::user();

        // Aseguramos que exista el registro de RecursosClave
        $clave = RecursosClave::firstOrCreate([
            'proyecto_id' => $proyecto_id,
            'user_id'     => $user->id,
        ]);

        // Traemos todas las inversiones de este proyecto y usuario
        // incluyendo ambas relaciones de recurso
        $inversiones = Etapa7::with(['recursoDisponible', 'recursoNecesario'])
            ->where('proyecto_id', $proyecto_id)
            ->where('user_id', $user->id)
            ->get();
        $proyectoactual = Project::find($proyecto_id);
        return Inertia::render('Etapa7', [
            'proyecto_id' => $proyecto_id,
            'disponibles' => $clave->disponibles,
            'necesarios'  => $clave->necesarios,
            'inversiones' => $inversiones,
            'proyectoactual' => $proyectoactual,
        ]);
    }

    // Guardar nueva inversión
    public function inversionesStore(Request $r)
    {
        $r->validate([
            'proyecto_id'     => 'required|integer',
            'tipo_origen'     => 'required|in:disponible,necesario',
            'recurso_id'      => 'required|integer',
            'cantidad'        => 'required|integer',
            'precio_unitario' => 'required|numeric',
            'clasificacion'   => 'required|string|max:255',
            'vida_util'       => 'required|integer',
        ]);

        $data = $r->all();
        $data['user_id'] = Auth::id();

        Etapa7::create($data);

        return back()->with('success', 'Inversión guardada');
    }

    // Actualizar inversión existente
    public function inversionesUpdate(Request $r, $id)
    {
        $r->validate([
            'tipo_origen'     => 'required|in:disponible,necesario',
            'recurso_id'      => 'required|integer',
            'cantidad'        => 'required|integer',
            'precio_unitario' => 'required|numeric',
            'clasificacion'   => 'required|string|max:255',
            'vida_util'       => 'required|integer',
        ]);

        Etapa7::findOrFail($id)->update($r->all());

        return back()->with('success', 'Inversión actualizada');
    }

    // Eliminar inversión
    public function inversionesDelete($id)
    {
        Etapa7::destroy($id);
        return back()->with('success', 'Inversión eliminada');
    }
}
