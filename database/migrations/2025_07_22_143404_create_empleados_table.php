<?php

// database/migrations/2025_07_22_000001_create_empleados_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');
            $table->string('nombre');
            $table->decimal('salario_nominal', 12, 2);
            $table->decimal('beneficios_pct', 5, 2);
            $table->decimal('salario_real', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
