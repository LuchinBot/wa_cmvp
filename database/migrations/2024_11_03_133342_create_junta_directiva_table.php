<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuntaDirectivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junta_directiva', function (Blueprint $table) {
            $table->increments("codjunta_directiva");
            $table->string("descripcion", 250)->nullable();
            $table->date("fecha_periodo_inicio");
            $table->date("fecha_periodo_fin");
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
        Schema::dropIfExists('junta_directiva');
    }
}
