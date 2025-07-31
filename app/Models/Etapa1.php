<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa1 extends Model
{
    protected $table = 'etapa1s';

    protected $fillable = [
        'id',
        'user_id',
        'proyecto_id',
        'nombre_emprendimiento',
        'nacionalidad_emprendedor',
        'pais_emprendedor',
        'ciudad_emprendedor',
        'entidad_asesora',
        'nombre_asesor',
        'nombre_representante_emprendimiento',
        'giro_negocio',
        'direccion_emprendimiento',
        'nro_emprendimiento',
        'calle_secundaria_emprendimiento',
        'barrio_distrito_emprendimiento',
        'telefono_emprendimiento',
        'email_emprendimiento',
        'ubicacion_geoespacial_emprendimiento',
        'cuenta_corriente',
        'tipo_institucion_financiera',
        'nombre_institucion_financiera',
        'ingresos_actuales',
        'motivo_no_ingresos',
        'tipo_emprendimiento',
        'etapa_emprendimiento',
        'url_imagen_emprendimiento',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }
}
