<?php

namespace App\Http\Controllers;

use App\Models\PropuestaValor;
use App\Models\RelacionClientes;
use App\Models\Recursosclave;
use App\Models\RecursoDisponible;
use App\Models\RecursoNecesario;
use App\Models\SegmentoClientes;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class Etapa4Controller extends Controller
{
    public function index($id)
    {

        $user = Auth::user();
        $propuestavalor = PropuestaValor::where('proyecto_id', $id)->where('user_id', $user->id)->first();

        return Inertia::render('Etapa4/Menu', [
            'proyecto_id' => $id,
            'propuestavalor' => $propuestavalor,
        ]);
    }
    //rutas propuesta de valor
    public function propuestavalor($id)
    {
        $user = Auth::user();
        $propuestavalor = PropuestaValor::where('proyecto_id', $id)->where('user_id', $user->id)->first();
        return Inertia::render('Etapa4/PropuestaValor', [
            'proyecto_id' => $id,
            'propuestavalor' => $propuestavalor,
        ]);
    }
    public function propuestavalorstore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'propuestavalor' => 'required|string|max:1000',
            'caracteristicas' => 'required|string|max:1000',
            'imagenes.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $validated['user_id'] = Auth::id();
        // Si hay imágenes, se manejan aquí (subida, almacenamiento, etc.)
        if ($request->hasFile('imagenes')) {
            $imagenes = [];
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('imagenes', 'public');
                $imagenes[] = $path;
            }
            $validated['imagenes'] = json_encode($imagenes);
        }
        PropuestaValor::create($validated);
        return redirect()->back()->with('success', 'Propuesta de valor guardada correctamente');
    }
    public function propuestavalorupdate(Request $request)
    {

        $validated = $request->validate([
            'propuestavalor' => 'required|string|max:1000',
            'caracteristicas' => 'required|string|max:1000',
            'proyecto_id' => 'required|integer',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $validated['user_id'] = Auth::id();

        // Obtener el registro actual para conservar las imágenes existentes
        $propuestaActual = PropuestaValor::where('proyecto_id', $request->proyecto_id)->where('user_id', Auth::id())->first();
        // Si hay imágenes, se añaden a las existentes
        if ($request->hasFile('imagenes')) {
            // Obtener imágenes existentes
            $imagenesExistentes = [];
            if ($propuestaActual && $propuestaActual->imagenes) {
                $imagenesExistentes = json_decode($propuestaActual->imagenes, true) ?? [];
            }

            // Procesar nuevas imágenes
            $nuevasImagenes = [];
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('imagenes', 'public');
                $nuevasImagenes[] = $path;
            }

            // Combinar imágenes existentes con las nuevas
            $todasLasImagenes = array_merge($imagenesExistentes, $nuevasImagenes);
            $validated['imagenes'] = json_encode($todasLasImagenes);
        } else {
            // Si no hay nuevas imágenes, mantener las existentes
            if ($propuestaActual && $propuestaActual->imagenes) {
                $validated['imagenes'] = $propuestaActual->imagenes;
            } else {
                $validated['imagenes'] = null;
            }
        }
        PropuestaValor::where('proyecto_id', $request->proyecto_id)->where('user_id', Auth::id())->update($validated);
        return redirect()->back()->with('success', 'Propuesta de valor actualizada correctamente');
    }
    public function eliminarImagen(Request $request, $id)
    {

        $validated = $request->validate([
            'ruta_imagen' => 'required|string'
        ]);

        $propuesta = PropuestaValor::where('proyecto_id', $id)
            ->where('user_id', Auth::id())
            ->first();


        // Obtener imágenes actuales
        $imagenesActuales = [];
        if ($propuesta->imagenes) {
            $imagenesActuales = json_decode($propuesta->imagenes, true) ?? [];
        }

        // Eliminar la imagen específica del array
        $imagenesActualizadas = array_filter($imagenesActuales, function ($imagen) use ($validated) {
            return $imagen !== $validated['ruta_imagen'];
        });

        // Reindexar el array
        $imagenesActualizadas = array_values($imagenesActualizadas);

        // Actualizar en la base de datos
        $propuesta->update([
            'imagenes' => json_encode($imagenesActualizadas)
        ]);

        // Eliminar el archivo físico del servidor
        if ($validated['ruta_imagen']) {
            $imagePath = public_path('storage/' . $validated['ruta_imagen']);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    //rutas segmento de clientes
    public function segmentoclientes($id)
    {
        $user = Auth::user();
        $segmentoclientes = SegmentoClientes::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->first();
        return Inertia::render('Etapa4/SegmentoClientes', [
            'proyecto_id' => $id,
            'segmentoclientes' => $segmentoclientes,
        ]);
    }
    public function segmentoclientesstore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'caracteristicas_segmento' => 'required|string|max:1000',
            'segmento_empresas_geografico' => 'nullable|string|max:1000',
            'segmento_empresas_nivel_ingreso' => 'nullable|string|max:1000',
            'segmento_empresas_numero_empleador' => 'nullable|string|max:1000',
            'segmento_empresas_tamano_empresa' => 'nullable|string|max:1000',
            'segmento_personas_conductal' => 'nullable|string|max:1000',
            'segmento_personas_geografico' => 'nullable|string|max:1000',
            'segmento_personas_psicografico' => 'nullable|string|max:1000',
            'segmento_personas_tamano_mercado' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        SegmentoClientes::create($validated);
        return redirect()->back()->with('success', 'Segmento de clientes guardado correctamente');
    }
    public function segmentoclientesupdate(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'caracteristicas_segmento' => 'required|string|max:1000',
            'segmento_empresas_geografico' => 'nullable|string|max:1000',
            'segmento_empresas_nivel_ingreso' => 'nullable|string|max:1000',
            'segmento_empresas_numero_empleador' => 'nullable|string|max:1000',
            'segmento_empresas_tamano_empresa' => 'nullable|string|max:1000',
            'segmento_personas_conductal' => 'nullable|string|max:1000',
            'segmento_personas_geografico' => 'nullable|string|max:1000',
            'segmento_personas_psicografico' => 'nullable|string|max:1000',
            'segmento_personas_tamano_mercado' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        SegmentoClientes::where('proyecto_id', $request->proyecto_id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Segmento de clientes actualizado correctamente');
    }

    //rutas relacion con clientes
    public function relacionclientes($id)
    {
        $user = Auth::user();
        $relacionclientes = RelacionClientes::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->first();
        return Inertia::render('Etapa4/RelacionClientes', [
            'proyecto_id' => $id,
            'relacionclientes' => $relacionclientes,
        ]);
    }
    public function relacionclientesstore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'relacion_clientes' => 'required|string|max:1000',
        ]); 
        $validated['user_id'] = Auth::id();
        RelacionClientes::create($validated);
        return redirect()->back()->with('success', 'Relación con clientes guardada correctamente');
    }
    public function relacionclientesupdate(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'relacion_clientes' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        RelacionClientes::where('proyecto_id', $request->proyecto_id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Relación con clientes actualizada correctamente');
    }

    // Mostrar RecursosClave + listas
public function recursosclave($id)
{
    $user = Auth::user();
    $clave = RecursosClave::firstOrCreate(
        ['proyecto_id'=>$id, 'user_id'=>$user->id]
    );

    return Inertia::render('Etapa4/RecursosClave', [
        'proyecto_id'    => $id,
        'recursos_clave' => $clave,
        'disponibles'    => $clave->disponibles,
        'necesarios'     => $clave->necesarios,
    ]);
}

// CRUD Recursos Disponibles
public function disponiblesStore(Request $r)
{
    $r->validate([
        'recursos_clave_id'=>'required|integer',
        'tipo_recurso'=>'required|string',
        'descripcion'=>'required|string',
        'cantidad'=>'required|integer',
        'estado'=>'required|string',
        'importancia'=>'required|string',
        'descripcion_importancia'=>'nullable|string',
    ]);
    RecursoDisponible::create($r->all());
    return back()->with('success','Recurso disponible añadido');
}

public function disponiblesUpdate(Request $r, $id)
{
    $r->validate([
        'tipo_recurso'=>'required|string',
        'descripcion'=>'required|string',
        'cantidad'=>'required|integer',
        'estado'=>'required|string',
        'importancia'=>'required|string',
        'descripcion_importancia'=>'nullable|string',
    ]);
    RecursoDisponible::findOrFail($id)->update($r->all());
    return back()->with('success','Recurso disponible actualizado');
}

public function disponiblesDelete($id)
{
    RecursoDisponible::destroy($id);
    return back()->with('success','Recurso disponible eliminado');
}

// CRUD Recursos Necesarios
public function necesariosStore(Request $r)
{
    $r->validate([
        'recursos_clave_id'=>'required|integer',
        'tipo_recurso'=>'required|string',
        'descripcion'=>'required|string',
        'cantidad'=>'required|integer',
        'aporte_objetivo'=>'required|string',
        'porcentaje'=>'required|numeric',
        'precio'=>'required|numeric',
        'puede_adquirir'=>'required|boolean',
    ]);
    RecursoNecesario::create($r->all());
    return back()->with('success','Recurso necesario añadido');
}

public function necesariosUpdate(Request $r, $id)
{
    $r->validate([
        'tipo_recurso'=>'required|string',
        'descripcion'=>'required|string',
        'cantidad'=>'required|integer',
        'aporte_objetivo'=>'required|string',
        'porcentaje'=>'required|numeric',
        'precio'=>'required|numeric',
        'puede_adquirir'=>'required|boolean',
    ]);
    RecursoNecesario::findOrFail($id)->update($r->all());
    return back()->with('success','Recurso necesario actualizado');
}

public function necesariosDelete($id)
{
    RecursoNecesario::destroy($id);
    return back()->with('success','Recurso necesario eliminado');
}

}
