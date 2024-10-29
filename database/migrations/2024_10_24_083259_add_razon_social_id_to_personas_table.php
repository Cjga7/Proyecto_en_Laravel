<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up()
{
    Schema::table('personas', function (Blueprint $table) {
        $table->unsignedBigInteger('razon_social_id')->nullable()->after('tipo_persona'); // Columna nullable
        $table->foreign('razon_social_id')->references('id')->on('razon_social')->onDelete('set null'); // Llave foránea
    });
}

public function down()
{
    Schema::table('personas', function (Blueprint $table) {
        $table->dropForeign(['razon_social_id']);
        $table->dropColumn('razon_social_id');
    });
}

};
