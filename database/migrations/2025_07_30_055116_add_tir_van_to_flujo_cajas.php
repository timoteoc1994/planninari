<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega los campos de TNA, TIR y VAN a la tabla flujo_cajas.
     */
    public function up(): void
    {
        Schema::table('flujo_cajas', function (Blueprint $table) {
$table->decimal('tna_inversion_alternativa', 5, 2)->default(0)->after('pagos_financiamiento');
            $table->decimal('tir_anual', 8, 2)->nullable()->after('tna_inversion_alternativa');
            $table->decimal('van', 15, 2)->nullable()->after('tir_anual');
        });
    }

    /**
     * Revierte los cambios.
     */
    public function down(): void
    {
        Schema::table('flujo_cajas', function (Blueprint $table) {
            $table->dropColumn(['tna_inversion_alternativa', 'tir_anual', 'van']);
        });
    }
};
