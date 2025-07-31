<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etapa1s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('proyecto_id')->constrained('projects')->onDelete('cascade');
            $table->string('nombre_emprendimiento')->nullable();
            $table->string('nacionalidad_emprendedor')->nullable();
            $table->string('pais_emprendedor')->nullable();
            $table->string('ciudad_emprendedor')->nullable();
            $table->string('entidad_asesora')->nullable();
            $table->string('nombre_asesor')->nullable();
            $table->string('nombre_representante_emprendimiento')->nullable();
            $table->string('giro_negocio')->nullable();
            $table->string('direccion_emprendimiento')->nullable();
            $table->string('nro_emprendimiento')->nullable();
            $table->string('calle_secundaria_emprendimiento')->nullable();
            $table->string('barrio_distrito_emprendimiento')->nullable();
            $table->string('telefono_emprendimiento')->nullable();
            $table->string('email_emprendimiento')->nullable();
            $table->string('ubicacion_geoespacial_emprendimiento')->nullable();
            $table->string('cuenta_corriente')->nullable();
            $table->string('tipo_institucion_financiera')->nullable();
            $table->string('nombre_institucion_financiera')->nullable();
            $table->string('ingresos_actuales')->nullable();
            $table->string('motivo_no_ingresos')->nullable();
            $table->string('tipo_emprendimiento')->nullable();
            $table->string('etapa_emprendimiento')->nullable();
            $table->string('url_imagen_emprendimiento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapa1s');
    }
};
