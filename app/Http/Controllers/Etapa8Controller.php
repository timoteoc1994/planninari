<?php

// app/Http/Controllers/Etapa8Controller.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Etapa8;
use App\Models\PropuestaValor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class Etapa8Controller extends Controller
{
    // Menú principal de Etapa 8
    public function index($id)
    {
        $user = Auth::user();
        $propuestavalor = PropuestaValor::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->first();

        return Inertia::render('Etapa8/Menu', [
            'proyecto_id'     => $id,
            'propuestavalor'  => $propuestavalor,
        ]);
    }

    // Mostrar formulario de creación
    public function create($id)
    {
        return Inertia::render('Etapa8/RecetaEstandar', [
            'proyecto_id' => $id,
        ]);
    }

    // Guardar nueva Etapa 8
    public function store(Request $request, $id)
    {
        $user = Auth::user();

        $etapa = Etapa8::create([
            'proyecto_id' => $id,
            'user_id'     => $user->id,
            'data'        => $request->input('data'),
        ]);

        return redirect()->route('etapa8.show', [
            'id'      => $id,
            'etapa8'  => $etapa->id,
        ]);
    }

    // Ver detalle de Etapa 8
    public function show($id, $etapa8)
    {
        $etapa = Etapa8::where('proyecto_id', $id)
            ->where('id', $etapa8)
            ->firstOrFail();

        return Inertia::render('Etapa8/Show', [
            'etapa8' => $etapa,
        ]);
    }

    // Formulario de edición
    public function edit($id, $etapa8)
    {
        $etapa = Etapa8::where('proyecto_id', $id)
            ->where('id', $etapa8)
            ->firstOrFail();

        return Inertia::render('Etapa8/Edit', [
            'etapa8' => $etapa,
        ]);
    }

    // Actualizar Etapa 8
    public function update(Request $request, $id, $etapa8)
    {
        $etapa = Etapa8::where('proyecto_id', $id)
            ->where('id', $etapa8)
            ->firstOrFail();

        $etapa->update([
            'data' => $request->input('data'),
        ]);

        return redirect()->route('etapa8.show', [
            'id'      => $id,
            'etapa8'  => $etapa->id,
        ]);
    }

    // Eliminar Etapa 8
    public function destroy($id, $etapa8)
    {
        $etapa = Etapa8::where('proyecto_id', $id)
            ->where('id', $etapa8)
            ->firstOrFail();

        $etapa->delete();

        return redirect()->route('etapa8.index', [
            'id' => $id,
        ]);
    }
}

