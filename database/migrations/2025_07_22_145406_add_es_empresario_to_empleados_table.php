<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('empleados', function (Blueprint $table) {
        $table->boolean('es_empresario')->default(false)->after('salario_real');
    });
}

public function down()
{
    Schema::table('empleados', function (Blueprint $table) {
        $table->dropColumn('es_empresario');
    });
}

};
