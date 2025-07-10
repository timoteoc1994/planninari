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
        Schema::create('integrantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
           $table->unsignedBigInteger('proyecto_id');
            $table->string('nombre');
            $table->string('apellido'); 
            $table->string('nacionalidad')->nullable();
            $table->string('pais_residencia')->nullable();
            $table->string('ciudad_residencia')->nullable();
            $table->string('sector')->nullable();
            $table->string('tipo_identificacion')->nullable();
            $table->string('numero_identificacion')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('discapcidad')->nullable();
            $table->string('dispone_cuenta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrantes');
    }
};
