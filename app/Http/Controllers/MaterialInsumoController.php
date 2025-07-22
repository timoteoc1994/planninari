<?php
// app/Http/Controllers/MaterialInsumoController.php

namespace App\Http\Controllers;

use App\Models\MaterialInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\RecetaEstandar;

class MaterialInsumoController extends Controller
{
    public function index($proyectoId)
    {
        $userId = Auth::id();

        $insumos = MaterialInsumo::where('proyecto_id', $proyectoId)
            ->where('user_id', $userId)
            ->get();

        $recetas = RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('user_id', $userId)
            ->get(['ref', 'nombre_producto']);

        return Inertia::render('Etapa8/MaterialInsumo', [
            'proyecto_id' => $proyectoId,
            'insumos'     => $insumos,
            'recetas'     => $recetas,
        ]);
    }

    public function store(Request $request, $proyectoId)
    {
        $request->validate([
            'ref'         => 'required|string|max:10',
            'ingrediente' => 'required|string',
            'descripcion' => 'nullable|string',
            'peso_g'      => 'nullable|numeric',
            'valor_usd'   => 'required|numeric',
        ]);

        MaterialInsumo::create([
            'proyecto_id' => $proyectoId,
            'user_id'     => Auth::id(),
            'ref'         => $request->ref,
            'ingrediente' => $request->ingrediente,
            'descripcion' => $request->descripcion,
            // si no envían peso_g, guardamos 0
            'peso_g'      => $request->peso_g ?? 0,
            'valor_usd'   => $request->valor_usd,
        ]);

        return redirect()
            ->route('materialinsumo.index', ['id' => $proyectoId]);
    }

    public function update(Request $request, $proyectoId, $id)
    {
        $request->validate([
            'ref'         => 'required|string|max:10',
            'ingrediente' => 'required|string',
            'descripcion' => 'nullable|string',
            'peso_g'      => 'nullable|numeric',
            'valor_usd'   => 'required|numeric',
        ]);

        $insumo = MaterialInsumo::where('proyecto_id', $proyectoId)
            ->where('id', $id)
            ->firstOrFail();

        $insumo->update([
            'ref'         => $request->ref,
            'ingrediente' => $request->ingrediente,
            'descripcion' => $request->descripcion,
            'peso_g'      => $request->peso_g ?? 0,
            'valor_usd'   => $request->valor_usd,
        ]);

        return redirect()
            ->route('materialinsumo.index', ['id' => $proyectoId]);
    }

    public function destroy($proyectoId, $id)
    {
        MaterialInsumo::where('proyecto_id', $proyectoId)
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('materialinsumo.index', ['id' => $proyectoId]);
    }
}
