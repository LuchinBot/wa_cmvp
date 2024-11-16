<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegranteJuntaDirectivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrante_junta_directiva', function (Blueprint $table) {
            $table->increments("codintegrante_junta_directiva");
            $table->integer("codjunta_directiva");
            $table->integer("codcargo");
            $table->integer("codcolegiado");
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
        Schema::dropIfExists('integrante_junta_directiva');
    }
}
