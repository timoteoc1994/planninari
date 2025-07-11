<?php

namespace App\Http\Controllers;

use App\Models\ActividadesClave;
use App\Models\Canales;
use App\Models\ProcesoComercial;
use App\Models\ProcesoProductivo;
use App\Models\PropuestaValor;
use App\Models\RelacionClientes;
use App\Models\SegmentoClientes;
use App\Models\AlianzaClave;
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

    //funciones para canales
    public function canales($id)
    {
        $user = Auth::user();
        $canales = Canales::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->first();
        return Inertia::render('Etapa4/Canales', [
            'proyecto_id' => $id,
            'canales' => $canales,
        ]);
    }
    public function canalesstore(Request $request)
    {

        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'canalescomunicacion' => 'nullable|string|max:1000',
            'canalesdistribucion' => 'nullable|string|max:1000',
            'facebook' => 'nullable|string|max:1000',
            'instagram' => 'nullable|string|max:1000',
            'whatsapp' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|string|max:1000',
            'tiktok' => 'nullable|string|max:1000',
            'twitter' => 'nullable|string|max:1000',
            'youtube' => 'nullable|string|max:1000',
            'web' => 'nullable|string|max:1000',
            'otro' => 'nullable|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        Canales::create($validated);
        return redirect()->back()->with('success', 'Canales guardados correctamente');
    }
    public function canalesupdate(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'canalescomunicacion' => 'required|string|max:1000',
            'canalesdistribucion' => 'nullable|string|max:1000',
            'facebook' => 'nullable|string|max:1000',
            'instagram' => 'nullable|string|max:1000',
            'whatsapp' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|string|max:1000',
            'tiktok' => 'nullable|string|max:1000',
            'twitter' => 'nullable|string|max:1000',
            'youtube' => 'nullable|string|max:1000',
            'web' => 'nullable|string|max:1000',
            'otro' => 'nullable|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        Canales::where('proyecto_id', $request->proyecto_id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Canales actualizados correctamente');
    }

    //funciones para actividades clave
    public function actividadesclave($id)
    {
        $user = Auth::user();
        $actividadesclave = ActividadesClave::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->first();
        $procesocomercial = ProcesoComercial::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->get();
        $procesoproductivo = ProcesoProductivo::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->get();
        return Inertia::render('Etapa4/ActividadesClave', [
            'proyecto_id' => $id,
            'actividadesclave' => $actividadesclave,
            'procesocomercial' => $procesocomercial,
            'procesoproductivo' => $procesoproductivo,
        ]);
    }
    public function actividadesclavestore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'actividad_clave' => 'required|string|max:1000',
            'archivo' => 'nullable|max:5048',
        ]);
        $validated['user_id'] = Auth::id();
        // Manejo de imagen
        if ($request->hasFile('archivo')) {
            $validated['archivo'] = $request->file('archivo')->store('actividadesclave', 'public');
        } else {
            // Si es update y no sube nueva imagen, mantener la anterior
            unset($validated['archivo']);
        }
        ActividadesClave::create($validated);
        return redirect()->back()->with('success', 'Actividad clave guardada correctamente');
    }
    public function actividadesclaveupdate(Request $request, $id)
    {
       
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'actividad_clave' => 'required|string|max:1000',
            'archivo' => 'nullable|max:5048',
        ]);
        $validated['user_id'] = Auth::id();

        // Manejo de imagen
        if ($request->hasFile('archivo')) {
            $validated['archivo'] = $request->file('archivo')->store('actividadesclave', 'public');
            //eliminar archivo anterior si existe
            $actividadClave = ActividadesClave::find($id);
            if ($actividadClave && $actividadClave->archivo) {
                $oldFilePath = public_path('storage/' . $actividadClave->archivo);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        } else {
            // Si es update y no sube nueva imagen, mantener la anterior
            unset($validated['archivo']);
        }

        ActividadesClave::where('id', $id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Actividad clave actualizada correctamente');
    }

    //sub rutas de actividades clave -proceso productivo
    public function procesoProductivoStore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'paso' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        ProcesoProductivo::create($validated);
        return redirect()->back()->with('success', 'Proceso productivo guardado correctamente');
    }

    public function procesoProductivoUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'paso' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        ProcesoProductivo::where('id', $id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Proceso productivo actualizado correctamente');
    }
    public function procesoProductivoDestroy($id)
    {
        $user = Auth::user();
        ProcesoProductivo::where('id', $id)
            ->where('user_id', $user->id)
            ->delete();
        return redirect()->back()->with('success', 'Proceso productivo eliminado correctamente');
    }
    //sub rutas de actividades clave -proceso comercial
    public function procesoComercialStore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'paso' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        ProcesoComercial::create($validated);
        return redirect()->back()->with('success', 'Proceso comercial guardado correctamente');
    }
    public function procesoComercialUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'paso' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        ProcesoComercial::where('id', $id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Proceso comercial actualizado correctamente');
    }
    public function procesoComercialDestroy($id)
    {
        $user = Auth::user();
        ProcesoComercial::where('id', $id)
            ->where('user_id', $user->id)
            ->delete();
        return redirect()->back()->with('success', 'Proceso comercial eliminado correctamente');
    }

    //funciones alianzas clave 
    public function alianzasclave($id)
    {
        $user = Auth::user();
        $alianzasclave = AlianzaClave::where('proyecto_id', $id)
            ->where('user_id', $user->id)
            ->get();
        return Inertia::render('Etapa4/AlianzaClave', [
            'proyecto_id' => $id,
            'alianzasclave' => $alianzasclave,
        ]);
    }
    public function alianzasclavestore(Request $request)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'nombrealiado' => 'required|string|max:250',
            'descripcion' => 'required|string|max:1000',
        ]); 
        $validated['user_id'] = Auth::id();
        AlianzaClave::create($validated);
        return redirect()->back()->with('success', 'Alianzas clave guardadas correctamente');
    }
    public function alianzasclaveupdate(Request $request, $id)
    {
        $validated = $request->validate([
            'proyecto_id' => 'required|integer',
            'nombrealiado' => 'required|string|max:250',
            'descripcion' => 'required|string|max:1000',
        ]);
        $validated['user_id'] = Auth::id();
        AlianzaClave::where('id', $id)
            ->where('user_id', Auth::id())
            ->update($validated);
        return redirect()->back()->with('success', 'Alianzas clave actualizadas correctamente');
    }
    public function alianzasclavedestroy($id)
    {
        $user = Auth::user();
        AlianzaClave::where('id', $id)
            ->where('user_id', $user->id)
            ->delete();
        return redirect()->back()->with('success', 'Alianzas clave eliminadas correctamente');
    }
}
