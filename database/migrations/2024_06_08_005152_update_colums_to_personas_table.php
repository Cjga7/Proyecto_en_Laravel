<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //elimina llave foranea
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->dropColumn('documento_id');
        });
        //crear llave foranea new
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('documento_id')->after('estado')->constrained('documentos')->onDelete('cascade');
        });
        //Crear nuevo campo
        Schema::table('personas', function (Blueprint $table) {
            $table->string('numero_documento',20)->after('documento_id');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            //Eliminar llave foranea new
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->dropColumn('documento_id');
        });
             //crear llave foranea
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('documento_id')->after('estado')->unique()->constrained('documentos')->onDelete('cascade');
        });

        //eliminar campo numero_documento
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('numero_documento');
        });

    }
};
