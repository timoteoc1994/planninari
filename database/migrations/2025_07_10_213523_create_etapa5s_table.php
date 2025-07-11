<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapa5sTable extends Migration
{
    public function up()
    {
        Schema::create('etapa5s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()              // asume tabla `users`
                  ->cascadeOnDelete();
            $table->foreignId('proyecto_id')
                  ->constrained('projects')   // o tu tabla de proyectos
                  ->cascadeOnDelete();
            $table->text('igualdad_genero');  // ← renombrado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etapa5s');
    }
}
