<?php

// database/migrations/2025_07_21_000000_create_etapa8_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapa8Table extends Migration
{
    public function up()
    {
        Schema::create('etapa8', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->json('data')->nullable();  // Almacena datos específicos de la etapa
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etapa8');
    }
}
