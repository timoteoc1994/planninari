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
        Schema::create('flujo_cajas', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');
        $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            
            // Saldo inicial único
            $table->decimal('saldo_inicial', 12, 2)->default(0);

            // Secciones dinámicas como JSON
            $table->json('ingresos')->nullable();
            $table->json('gastos')->nullable();
            $table->json('financiamiento_recibido')->nullable();
            $table->json('pagos_financiamiento')->nullable();

            $table->timestamps();

            // Único registro por proyecto + usuario
            $table->unique(['proyecto_id', 'user_id'], 'ux_flujo_cajas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujo_cajas');
    }
};
