<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEjercicioprofesionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ejercicio_profesional', function (Blueprint $table) {
            $table->increments("codejercicio_profesional");
            $table->string("descripcion", 250);
            $table->string("abreviatura", 220)->nullable();
            $table->string("referencia", 250)->nullable();
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
        Schema::dropIfExists('ejercicio_profesional');
    }
}
