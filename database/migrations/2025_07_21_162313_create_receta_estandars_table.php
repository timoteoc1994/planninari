<?php

// database/migrations/2025_07_21_000001_create_receta_estandars_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetaEstandarsTable extends Migration
{
    public function up()
    {
        Schema::create('receta_estandars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // LISTADO DE PRODUCTOS DESARROLLADOS
            $table->string('ref', 10);
            $table->string('nombre_producto');
            $table->text('detalle')->nullable();
            $table->string('presentacion')->nullable();
            $table->decimal('precio_venta', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receta_estandars');
    }
}
