<?php

namespace App\Http\Controllers;

use App\Models\Etapa1;
use App\Models\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

class GenerarPdfController extends Controller
{
    public function index(Project $planes)
    {
        $dominio = env('APP_URL');
        $storagePath = 'storage/';
        
        $etapa1= Etapa1::where('proyecto_id', $planes->id)->first();
        $logoempresa = public_path('storage/' . $etapa1->url_imagen_emprendimiento);
        $mesyanio= date('F Y');
        //informacion de la empresa
        $nombre_empresa = $etapa1->nombre_emprendimiento;
        $nombre_representante_legal = $etapa1->nombre_emprendimiento;
        $actividad_economica = $etapa1->giro_negocio;
        $direccion = $etapa1->direccion_emprendimiento;
        $telefono = $etapa1->telefono_emprendimiento;
        $email = $etapa1->email_emprendimiento;

        $data = [
            'nombre' => $planes->name,
            'logoempresa' => $logoempresa,
            'mesyanio' => $mesyanio,
            //informacion de la empresa
            'nombre_empresa' => $nombre_empresa,
            'nombre_representante_legal' => $nombre_representante_legal,
            'actividad_economica' => $actividad_economica,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'email' => $email,
        ];

        $pdf = Pdf::loadView('pdf.documento', $data);

        return $pdf->download($planes->name . '.pdf');
    }
}
