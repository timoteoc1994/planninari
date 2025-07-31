<?php

namespace App\Http\Controllers;

use App\Models\Planes;
use App\Models\Project;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Planes/Plan', [
            'planes' => Planes::paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Planes::create($request->only('nombre', 'descripcion'));

        return back()->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Planes $planes)
    {

        $proyectos = Project::with(['Etapa1' => function ($q) {
            $q->select('id', 'proyecto_id', 'url_imagen_emprendimiento');
        }])->where('plan_id', $planes->id)->paginate(10);
        $planes = Planes::findOrFail($planes->id);
        return inertia('Planes/PlanDetalles', [
            'planes' => $planes,
            'proyectos' => $proyectos,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Planes $planes) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Planes $planes)
    {

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $planes->update($validatedData);

        return back()->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planes $planes)
    {
        $planes->delete();

        return back()->with('success', 'Plan deleted successfully.');
    }
    public function showplan(Project $planes)
    {
       
        $proyecto= Project::where('id', $planes->id)->first();
        return inertia('Planes/VistaPlan', [
            'project' => $proyecto,
        ]);
    }
}
