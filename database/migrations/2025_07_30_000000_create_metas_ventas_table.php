<?php
// database/migrations/2025_07_30_000000_create_metas_ventas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasVentasTable extends Migration
{
    public function up()
    {
        Schema::create('metas_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('projects')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->string('ref');          // referencia al producto (PR1, PR2, …)
            $table->integer('meta_100')->default(0);
            $table->integer('meta_125')->default(0);
            $table->timestamps();

            $table->unique(['proyecto_id','user_id','ref']);
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->cascadeOnDelete();
            $table->foreign('user_id')    ->references('id')->on('users')    ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('metas_ventas');
    }
}
