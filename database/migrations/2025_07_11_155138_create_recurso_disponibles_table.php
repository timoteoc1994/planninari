<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('recursos_disponibles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recursos_clave_id');
            $table->string('tipo_recurso');
            $table->text('descripcion');
            $table->integer('cantidad');
            $table->string('estado');
            $table->string('importancia');
            $table->text('descripcion_importancia')->nullable();
            $table->timestamps();

            $table->foreign('recursos_clave_id')
                  ->references('id')->on('recursos_claves')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recursos_disponibles');
    }
};
