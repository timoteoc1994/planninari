<?php

namespace App\Http\Controllers;

use App\Models\Etapa9;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class Etapa9Controller extends Controller
{
    public function index($proyecto_id)
    {
        $etapa9 = Etapa9::where('proyecto_id', $proyecto_id)->first();
        $proyectoactual = Project::find($proyecto_id);
        return Inertia::render('Etapa9', [
            'etapa9'      => $etapa9,
            'proyecto_id' => $proyecto_id,
            'proyectoactual' => $proyectoactual,
        ]);
    }

    public function store(Request $request)
    {
        // 1) Validación apuntando a la tabla correcta 'projects'
        $validated = $request->validate([
            'proyecto_id' => 'required|integer|exists:projects,id',
            'video'       => 'required|file|mimes:mp4,avi,mpeg|max:102400', // 100 MB
        ]);

        // Añadimos el user_id
        $validated['user_id'] = Auth::id();

        // 2) Si viene el archivo, lo subimos a public/videos
        if ($request->hasFile('video')) {
            $video    = $request->file('video');
            $filename = time().'_'.preg_replace('/\s+/', '_', $video->getClientOriginalName());
            $path     = $video->storeAs('videos', $filename, 'public');
            $validated['video_pitch_path'] = $path;
        }

        // 3) Creamos el registro
        Etapa9::create($validated);

        return redirect()->back()
                         ->with('success', 'Video guardado correctamente.');
    }

    public function update(Request $request, Etapa9 $etapa9)
    {
        // 1) Validación
        $validated = $request->validate([
            'proyecto_id' => 'required|integer|exists:projects,id',
            'video'       => 'nullable|file|mimes:mp4,avi,mpeg|max:102400',
        ]);

        // Añadimos el user_id
        $validated['user_id'] = Auth::id();

        // 2) Si viene un video nuevo, borra el viejo y guarda el nuevo
        if ($request->hasFile('video')) {
            // borrar anterior
            if ($etapa9->video_pitch_path
                && Storage::disk('public')->exists($etapa9->video_pitch_path)
            ) {
                Storage::disk('public')->delete($etapa9->video_pitch_path);
            }

            $file     = $request->file('video');
            $filename = time().'_'.preg_replace('/\s+/', '_', $file->getClientOriginalName());
            // guarda en storage/app/public/videos usando storeAs para consistencia
            $path = $file->storeAs('videos', $filename, 'public');
            $validated['video_pitch_path'] = $path;
        }

        // 3) Actualiza el registro con los datos validados
        $etapa9->update($validated);

        return redirect()->back()
                         ->with('success', 'Video actualizado correctamente.');
    }

    public function destroyVideo(Etapa9 $etapa9)
{
    if ($etapa9->video_pitch_path
        && Storage::disk('public')->exists($etapa9->video_pitch_path)
    ) {
        Storage::disk('public')->delete($etapa9->video_pitch_path);
    }

    // Limpia el campo en la base de datos
    $etapa9->video_pitch_path = null;
    $etapa9->save();

    return back()->with('success', 'Video eliminado correctamente.');
}

}