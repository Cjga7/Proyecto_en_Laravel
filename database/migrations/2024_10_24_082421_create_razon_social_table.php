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
        Schema::create('razon_social', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social')->nullable(); // Permitir valores NULL
            $table->bigInteger('persona_id')->unsigned();
            $table->timestamps();

            // Relación con la tabla personas
            $table->foreign('persona_id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('razon_social');
    }

};
