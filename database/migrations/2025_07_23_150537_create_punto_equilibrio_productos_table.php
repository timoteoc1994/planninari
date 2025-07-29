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
        Schema::create('punto_equilibrio_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('unidad_medida');
            $table->decimal('participacion_pct', 5, 2);
            $table->unsignedInteger('unidades_diarias');
            $table->decimal('margen_producto', 10, 2);
            $table->decimal('margen_contribucion_pct', 5, 2);
            $table->decimal('margen_ponderado', 5, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punto_equilibrio_productos');
    }
};
