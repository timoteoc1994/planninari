<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inversiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->unsignedBigInteger('user_id');

            // Origen del recurso: disponible o necesario
            $table->enum('tipo_origen', ['disponible','necesario']);
            $table->unsignedBigInteger('recurso_id');

            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->string('clasificacion');
            $table->integer('vida_util');

            $table->timestamps();

            // FKs
            $table->foreign('proyecto_id')
                  ->references('id')->on('proyectos')
                  ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inversiones');
    }
};
