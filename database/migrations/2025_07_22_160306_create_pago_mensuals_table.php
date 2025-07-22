<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagos_mensuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
              ->constrained('projects')
              ->onDelete('cascade');
            $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');
            $table->string('concepto');
            $table->decimal('valor', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_mensuales');
    }
};
