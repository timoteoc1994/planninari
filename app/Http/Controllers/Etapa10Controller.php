<?php

namespace App\Http\Controllers;

use App\Models\Etapa10;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class Etapa10Controller extends Controller
{
    public function index($proyecto_id)
    {
        $etapa10 = Etapa10::where('proyecto_id', $proyecto_id)->first();
        $proyectoactual = Project::find($proyecto_id);
        return Inertia::render('Etapa10', [
            'etapa10'     => $etapa10,
            'proyecto_id' => $proyecto_id,
            'proyectoactual' => $proyectoactual,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proyecto_id'     => 'required|integer|exists:projects,id',
            'documentos.*'    => 'required|file|mimes:pdf,doc,docx,xlsx,jpg,png|max:5120', 
        ]);

        $data['user_id'] = Auth::id();

        // Subida múltiple
        $paths = [];
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $doc) {
                $paths[] = Storage::disk('public')
                                 ->putFile('documentos', $doc);
            }
        }
        $data['document_paths'] = json_encode($paths);
        
        Etapa10::create($data);

        return back()->with('success', 'Documentos guardados correctamente.');
    }

    public function update(Request $request, Etapa10 $etapa10)
    {
        // sólo proyecto_id
        $data = $request->validate([
            'proyecto_id'     => 'required|integer|exists:projects,id',
            'documentos.*'    => 'nullable|file|mimes:pdf,doc,docx,xlsx,jpg,png|max:5120',
        ]);
        $data['user_id'] = Auth::id();

        // Si suben nuevos documentos, anexarlos a los existentes
        $existing = $etapa10->document_paths
                          ? json_decode($etapa10->document_paths, true)
                          : [];
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $doc) {
                $existing[] = Storage::disk('public')
                                     ->putFile('documentos', $doc);
            }
        }
        $data['document_paths'] = json_encode($existing);

        $etapa10->update($data);

        return back()->with('success', 'Etapa 10 actualizada correctamente.');
    }

    public function destroyDocument(Etapa10 $etapa10, $index)
    {
        $docs = $etapa10->document_paths
                       ? json_decode($etapa10->document_paths, true)
                       : [];

        if (isset($docs[$index]) && Storage::disk('public')->exists($docs[$index])) {
            Storage::disk('public')->delete($docs[$index]);
            array_splice($docs, $index, 1);
            $etapa10->update(['document_paths' => json_encode($docs)]);
        }

        return back()->with('success', 'Documento eliminado.');
    }
}
