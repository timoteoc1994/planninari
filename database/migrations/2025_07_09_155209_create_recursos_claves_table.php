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
        Schema::create('recursos_claves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('proyecto_id')->constrained('projects')->onDelete('cascade');
            
            // Campos específicos para recursos claves
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['humano', 'tecnologico', 'financiero', 'material', 'intelectual'])->default('material');
            $table->decimal('costo_estimado', 10, 2)->nullable();
            $table->string('proveedor')->nullable();
            $table->enum('disponibilidad', ['disponible', 'necesario', 'en_proceso'])->default('necesario');
            $table->date('fecha_necesaria')->nullable();
            $table->text('notas')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos_claves');
    }
};