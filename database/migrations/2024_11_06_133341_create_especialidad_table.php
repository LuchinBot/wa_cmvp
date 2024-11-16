<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecialidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidad', function (Blueprint $table) {
            $table->increments("codespecialidad");
            $table->string("identificador", 10)->nullable()->uniqid();
            $table->string("descripcion", 250);
            $table->string("abreviatura", 120)->nullable();
            $table->string("nota", 120)->nullable()->comment("Datos adicionales de la especialidad");
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
        Schema::dropIfExists('especialidad');
    }
}
