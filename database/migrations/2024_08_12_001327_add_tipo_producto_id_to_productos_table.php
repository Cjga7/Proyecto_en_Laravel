<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_producto_id')->after('id');

            // Define la clave foránea
            $table->foreign('tipo_producto_id')->references('id')->on('tipos_productos')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Elimina la clave foránea
            $table->dropForeign(['tipo_producto_id']);
            $table->dropColumn('tipo_producto_id');
        });
    }
};
