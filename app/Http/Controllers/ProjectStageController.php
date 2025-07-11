<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ProjectStageController extends Controller
{
    public function index(){
        $user = Auth::user();
        $proyecto = Project::where('user_id', $user->id)->get();
        return Inertia::render('Dashboard', [
            'projects' => $proyecto
        ]);
    }
   public function projectsstore(Request $request)
    {
       $validated = $request->validate([
           'name' => 'required|string',
           'description' => 'required|string|max:1000',
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
}