<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('recursos_claves', function (Blueprint $table) {
            $table->unsignedBigInteger('proyecto_id')->after('id');
            $table->unsignedBigInteger('user_id')->after('proyecto_id');

            $table->foreign('proyecto_id')
                  ->references('id')->on('proyectos')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('recursos_claves', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id']);
            $table->dropColumn('proyecto_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
