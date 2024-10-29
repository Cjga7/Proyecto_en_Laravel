<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRazonSocialIdFromPersonasTable extends Migration
{
    public function up()
    {
        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea
            $table->dropForeign(['razon_social_id']);
            // Eliminar la columna razon_social_id
            $table->dropColumn('razon_social_id');
        });
    }

    public function down()
    {
        Schema::table('personas', function (Blueprint $table) {
            // Revertir el cambio si es necesario
            $table->bigInteger('razon_social_id')->unsigned()->nullable();
            // Re-establecer la clave foránea si es necesario
            $table->foreign('razon_social_id')->references('id')->on('razon_social')->onDelete('cascade');
        });
    }
}

