<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostoVariablesTable extends Migration
{
    public function up()
    {
        Schema::create('costo_variables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ref', 10);
            $table->string('unidad')->nullable();
            $table->unsignedInteger('unidades_mes');
            $table->decimal('costo_unitario', 12, 2);
            $table->decimal('venta_unitario', 12, 2);
            $table->decimal('total_costo', 14, 2);
            $table->decimal('total_venta', 14, 2);
            $table->unsignedTinyInteger('pct_costo_venta');
            $table->unsignedTinyInteger('pct_margen');
            $table->unsignedTinyInteger('pct_participacion');
            $table->timestamps();

            $table->index('ref');
        });
    }

    public function down()
    {
        Schema::dropIfExists('costo_variables');
    }
}
