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
            $table->string('razon_social', 80)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('razon_social', 80)->nullable(false)->change();
        });
    }

};
