<?php

// database/migrations/2025_08_01_000000_alter_flujo_cajas_precision.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFlujoCajasPrecision extends Migration
{
    public function up()
    {
        Schema::table('flujo_cajas', function (Blueprint $table) {
            // porcentaje de alternativa, hasta 9999.99%
            $table->decimal('tna_inversion_alternativa', 6, 2)->change();
            // TIR anual muy alta o VAN muy grande; damos 13 dígitos antes y 2 decimales
            $table->decimal('tir_anual', 15, 2)->change();
            $table->decimal('van', 18, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('flujo_cajas', function (Blueprint $table) {
            // Vuelve a tus valores originales; por ejemplo DECIMAL(8,2)
            $table->decimal('tna_inversion_alternativa', 8, 2)->change();
            $table->decimal('tir_anual', 8, 2)->change();
            $table->decimal('van', 8, 2)->change();
        });
    }
}
