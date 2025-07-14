<?php

namespace App\Http\Controllers;

use App\Models\Etapa9;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class Etapa9Controller extends Controller
{
    public function index($proyecto_id)
    {
        $etapa9 = Etapa9::where('proyecto_id', $proyecto_id)->first();

        return Inertia::render('Etapa9', [
            'etapa9'      => $etapa9,
            'proyecto_id' => $proyecto_id,
        ]);
    }

    public function store(Request $request)
    {
        // 1) Validación apuntando a la tabla correcta 'proyectos'
        $validated = $request->validate([
            'proyecto_id' => 'required|integer|exists:projects,id',
            'video'       => 'required|file|mimes:mp4,avi,mpeg|max:102400', // 100 MB
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
        // 1) Validación apuntando a la tabla correcta 'proyectos'
        $data = $request->validate([
            'proyecto_id' => 'required|integer|exists:projects,id',
            'video'       => 'nullable|file|mimes:mp4,avi,mpeg|max:102400',
        ]);

        // 2) Si hay archivo nuevo, eliminamos el anterior y guardamos el nuevo
        if ($request->hasFile('video')) {
            if ($etapa9->video_pitch_path
                && Storage::disk('public')->exists($etapa9->video_pitch_path)
            ) {
                Storage::disk('public')->delete($etapa9->video_pitch_path);
            }

            $file     = $request->file('video');
            $filename = time().'_'.preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $etapa9->video_pitch_path = Storage::disk('public')
                                              ->putFileAs('videos', $file, $filename);
        }

        // 3) Actualizamos proyecto_id
        $etapa9->proyecto_id = $data['proyecto_id'];
        $etapa9->save();

        return redirect()->back()
                         ->with('success', 'Video actualizado correctamente.');
    }
}
