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
        Schema::create('segmento_clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('proyecto_id')->constrained('projects')->onDelete('cascade');
            $table->text('caracteristicas_segmento')->nullable();
            $table->string('segmento_empresas_geografico')->nullable();
            $table->string('segmento_empresas_nivel_ingreso')->nullable();
            $table->string('segmento_empresas_numero_empleador')->nullable();
            $table->string('segmento_empresas_tamano_empresa')->nullable();
            $table->string('segmento_personas_conductal')->nullable();
            $table->string('segmento_personas_geografico')->nullable();
            $table->string('segmento_personas_psicografico')->nullable();
            $table->string('segmento_personas_tamano_mercado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segmento_clientes');
    }
};
