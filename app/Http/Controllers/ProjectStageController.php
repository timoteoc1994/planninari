<?php
namespace App\Http\Controllers;

use App\Models\Planes;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ProjectStageController extends Controller
{
    public function index(){
        $user = Auth::user();
        $proyecto = Project::where('user_id', $user->id)->get();

        //obtener todos los planes agregados por el administrador
        $planes = Planes::all();
        return Inertia::render('Dashboard', [
            'projects' => $proyecto,
            'planes' => $planes
        ]);
    }
    public function show($id)
    {
     
        $project = Project::findOrFail($id);
        return Inertia::render('ProjectStages', [
            'project' => $project,
        ]);
    }
   public function projectsstore(Request $request)
    {
       $validated = $request->validate([
           'name' => 'required|string',
           'description' => 'required|string|max:1000',
           'plan_id' => 'required|exists:planes,id',
       ]);
       $validated['user_id'] = Auth::id();

        Project::create($validated);

        return redirect()->back()->with('success', 'Datos guardados correctamente');
    }

    public function projectsupdate(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:1000',
            'description' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();

        $project = Project::findOrFail($id);
        $project->update($validated);

        return redirect()->back()->with('success', 'Datos actualizados correctamente');
    }
    public function projectsdestroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('success', 'Proyecto eliminado correctamente');
    }
    
}