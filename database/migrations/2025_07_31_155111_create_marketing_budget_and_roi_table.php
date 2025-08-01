<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('presupuesto_marketing', function (Blueprint $table) {
            $table->id();
                        // Relaciones
            $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');
        $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            $table->json('medios')->nullable(); // Array de objetos {nombre, valores[]}
            $table->json('costos_trimestrales')->nullable();  // Array de costos trimestrales
            $table->json('ganancias_trimestrales')->nullable();  // Array de ganancias trimestrales
            $table->json('roi_dolares')->nullable();  // Array de ROI en dólares
            $table->json('roi_porcentajes')->nullable();  // Array de ROI en porcentaje
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down(): void
    {
        // Corregir el nombre de la tabla aquí
        Schema::dropIfExists('presupuesto_marketing');
    }
};
