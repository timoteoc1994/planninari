<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etapa1;
use Inertia\Inertia;

class Etapas extends Controller
{
    //crear etapa1
    public function etapa1($id)
    {
        $etapa1 = Etapa1::where('proyecto_id', $id)->first();
        return Inertia::render('Etapa1', [
            'etapa1' => $etapa1,
            'proyecto_id' => $id,
        ]);
       
    }

public function storeOrUpdate(Request $request, $proyecto_id)
{
    
    $data = $request->validate([
        'nombre_emprendimiento' => 'nullable|string',
        'nacionalidad_emprendedor' => 'nullable|string',
        'pais_emprendedor' => 'nullable|string',
        'ciudad_emprendedor' => 'nullable|string',
        'entidad_asesora' => 'nullable|string',
        'nombre_asesor' => 'nullable|string',
        'nombre_representante_emprendimiento' => 'nullable|string',
        'giro_negocio' => 'nullable|string',
        'direccion_emprendimiento' => 'nullable|string',
        'nro_emprendimiento' => 'nullable|string',
        'calle_secundaria_emprendimiento' => 'nullable|string',
        'barrio_distrito_emprendimiento' => 'nullable|string',
        'ubicacion_geoespacial_emprendimiento' => 'nullable|string',
        'cuenta_corriente' => 'nullable|string',
        'tipo_institucion_financiera' => 'nullable|string',
        'nombre_institucion_financiera' => 'nullable|string',
        'ingresos_actuales' => 'nullable|string',
        'motivo_no_ingresos' => 'nullable|string',
        'tipo_emprendimiento' => 'nullable|string',
        'etapa_emprendimiento' => 'nullable|string',
        'url_imagen_emprendimiento' => 'nullable|image|max:2048',
    ]);
    
    $data['user_id'] = auth()->id();
    $data['proyecto_id'] = $proyecto_id;

    // Manejo de imagen
    if ($request->hasFile('url_imagen_emprendimiento')) {
        $data['url_imagen_emprendimiento'] = $request->file('url_imagen_emprendimiento')->store('emprendimientos', 'public');
    } else {
        // Si es update y no sube nueva imagen, mantener la anterior
        unset($data['url_imagen_emprendimiento']);
    }

    $etapa1 = Etapa1::updateOrCreate(
        ['proyecto_id' => $proyecto_id],
        $data
    );
    return redirect()->back()->with('success', 'Datos guardados correctamente');
}
}
