<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntoEquilibriosTable extends Migration
{
    public function up()
    {
        Schema::create('punto_equilibrios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('dias_por_semana')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('punto_equilibrios');
    }
}
