<?php
// database/migrations/2025_07_21_000002_create_material_insumos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialInsumosTable extends Migration
{
    public function up()
    {
        Schema::create('material_insumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');
            $table->string('ref', 10);
            $table->string('ingrediente');
            $table->text('descripcion')->nullable();
            $table->decimal('peso_g', 10, 2);
            $table->decimal('valor_usd', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_insumos');
    }
}
