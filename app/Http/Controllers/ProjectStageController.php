<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectStageController extends Controller
{
    public function show(Project $project)
    {
        // Puedes cargar etapas desde la base de datos si las tienes, por ahora solo 10 vacías
        $stages = collect(range(1, 10))->map(fn($i) => [
            'number' => $i,
            'name' => "Etapa $i",
            'content' => null,
        ]);

        return Inertia::render('ProjectStages', [
            'project' => $project,
            'stages' => $stages,
        ]);
    }
}