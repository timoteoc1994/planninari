<?php

// app/Http/Controllers/EmpleadoController.php
// app/Http/Controllers/EmpleadoController.php
namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EmpleadoController extends Controller
{
    public function index($proyectoId)
    {
        $userId   = Auth::id();
        $empleados = Empleado::where('proyecto_id', $proyectoId)
                       ->where('user_id', $userId)
                       ->get();

        return Inertia::render('Etapa8/Empleados', [
            'proyecto_id' => $proyectoId,
            'empleados'   => $empleados,
        ]);
    }

    public function store(Request $request, $proyectoId)
    {
        $data = $request->validate([
            'nombre'           => 'required|string',
            'salario_nominal'  => 'required|numeric',
            'beneficios_pct'   => 'required|numeric',
            'es_empresario'    => 'nullable|boolean',
        ]);

        $data['es_empresario'] = $data['es_empresario'] ?? false;
        $data['salario_real']  = $data['salario_nominal'] * (1 + $data['beneficios_pct']/100);

        Empleado::create(array_merge($data, [
            'proyecto_id' => $proyectoId,
            'user_id'     => Auth::id(),
        ]));

        return redirect()->route('empleados.index', ['id' => $proyectoId]);
    }

    public function update(Request $request, $proyectoId, Empleado $empleado)
    {
        $data = $request->validate([
            'nombre'           => 'required|string',
            'salario_nominal'  => 'required|numeric',
            'beneficios_pct'   => 'required|numeric',
            'es_empresario'    => 'nullable|boolean',
        ]);

        $data['es_empresario'] = $data['es_empresario'] ?? false;
        $data['salario_real']  = $data['salario_nominal'] * (1 + $data['beneficios_pct']/100);

        $empleado->update($data);

        return redirect()->route('empleados.index', ['id' => $proyectoId]);
    }

    public function destroy($proyectoId, Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index', ['id' => $proyectoId]);
    }
}
