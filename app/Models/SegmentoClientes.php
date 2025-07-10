<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegmentoClientes extends Model
{
    protected $table = 'segmento_clientes';

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'caracteristicas_segmento',
        'segmento_empresas_geografico',
        'segmento_empresas_nivel_ingreso',
        'segmento_empresas_numero_empleador',
        'segmento_empresas_tamano_empresa',
        'segmento_personas_conductal',
        'segmento_personas_geografico',
        'segmento_personas_psicografico',
        'segmento_personas_tamano_mercado',
    ];
}
