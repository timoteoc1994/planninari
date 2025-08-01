<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasVentasTable extends Migration
{
    public function up()
    {
        Schema::create('metas_ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('proyecto_id')
                  ->constrained('projects')
                  ->onDelete('cascade');

            $table->string('ref'); // referencia al producto (PR1, PR2, …)
            $table->integer('meta_100')->default(0);
            $table->integer('meta_125')->default(0);

            $table->timestamps();

            $table->unique(['proyecto_id', 'user_id', 'ref']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('metas_ventas');
    }
}
