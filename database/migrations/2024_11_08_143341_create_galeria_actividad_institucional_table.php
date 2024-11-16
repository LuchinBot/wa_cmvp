<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaleriaActividadInstitucionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galeria_actividad_institucional', function (Blueprint $table) {
            $table->increments("codgaleria_actividad_institucional");
            $table->integer("codactividad_institucional");
            $table->string("imagen", 60)->nullable();
            $table->integer("orden")->default(1);
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
        Schema::dropIfExists('galeria_actividad_institucional');
    }
}
