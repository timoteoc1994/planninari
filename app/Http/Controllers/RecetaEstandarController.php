<?php

namespace App\Http\Controllers;

use App\Models\RecetaEstandar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RecetaEstandarController extends Controller
{
    /**
     * Muestra el listado de Productos Desarrollados.
     */
    public function index($proyectoId)
    {
        $productos = RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Etapa8/RecetaEstandar', [
            'proyecto_id' => $proyectoId,
            'productos'   => $productos,
        ]);
    }

    /**
     * Ruta “create” redirige al índice (abrir modal).
     */
    public function create($proyectoId)
    {
        return redirect()
            ->route('recetaestandar.index', ['id' => $proyectoId]);
    }

    /**
     * Almacena un nuevo Producto Desarrollado.
     */
    public function store(Request $request, $proyectoId)
    {
        $request->validate([
            'nombre_producto' => 'required|string',
            'detalle'         => 'nullable|string',
            'presentacion'    => 'nullable|string',
            'precio_venta'    => 'required|numeric',
        ]);

        // Generar código automático PR1, PR2, …
        $count = RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('user_id', Auth::id())
            ->count();
        $ref = 'PR' . ($count + 1);

        RecetaEstandar::create([
            'proyecto_id'     => $proyectoId,
            'user_id'         => Auth::id(),
            'ref'             => $ref,
            'nombre_producto' => $request->nombre_producto,
            'detalle'         => $request->detalle,
            'presentacion'    => $request->presentacion,
            'precio_venta'    => $request->precio_venta,
        ]);

        return redirect()
            ->route('recetaestandar.index', ['id' => $proyectoId]);
    }

    /**
     * Muestra un producto en detalle.
     */
    public function show($proyectoId, $id)
    {
        $producto = RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('Menu/RecetaEstandar/Show', [
            'proyecto_id' => $proyectoId,
            'producto'    => $producto,
        ]);
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($proyectoId, $id)
    {
        $producto = RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('Menu/RecetaEstandar/Edit', [
            'proyecto_id' => $proyectoId,
            'producto'    => $producto,
        ]);
    }

    /**
     * Actualiza un producto existente.
     */
    public function update(Request $request, $proyectoId, $id)
    {
        $request->validate([
            'nombre_producto' => 'required|string',
            'detalle'         => 'nullable|string',
            'presentacion'    => 'nullable|string',
            'precio_venta'    => 'required|numeric',
        ]);

        $producto = RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('id', $id)
            ->firstOrFail();

        $producto->update($request->only([
            'nombre_producto',
            'detalle',
            'presentacion',
            'precio_venta',
        ]));

            return redirect()->route('recetaestandar.index', ['id' => $proyectoId]);

    }

    /**
     * Elimina un producto.
     */
    public function destroy($proyectoId, $id)
    {
        RecetaEstandar::where('proyecto_id', $proyectoId)
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('recetaestandar.index', ['id' => $proyectoId]);
    }
}
