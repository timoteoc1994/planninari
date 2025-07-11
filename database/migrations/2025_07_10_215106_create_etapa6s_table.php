<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapa6sTable extends Migration
{
    public function up()
    {
        Schema::create('etapa6s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()               // asume tabla `users`
                  ->cascadeOnDelete();
            $table->foreignId('proyecto_id')
                  ->constrained('projects')    // ajusta si tu tabla se llama distinto
                  ->cascadeOnDelete();
            $table->text('mitigacion_dano_ambiental');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etapa6s');
    }
}
