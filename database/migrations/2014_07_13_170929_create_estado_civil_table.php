<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoCivilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_civil', function (Blueprint $table) {
            $table->increments("codestado_civil");
            $table->string("descripcion", 120);
            $table->string("abreviatura", 60)->nullable();
            $table->string("identificador",10)->comment("Identificador unico por estado civil");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estado_civil');
    }
}
