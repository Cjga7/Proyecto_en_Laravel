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
        Schema::table('personas', function (Blueprint $table) {
            $table->string('nombre', 50)->nullable()->after('id');
            $table->string('primer_apellido', 50)->nullable()->after('nombre');
            $table->string('segundo_apellido', 50)->nullable()->after('primer_apellido');
        });
    }

    public function down()
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'primer_apellido', 'segundo_apellido']);
        });
    }

};
