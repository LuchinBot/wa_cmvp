<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColegiadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colegiado', function (Blueprint $table) {
            $table->increments("codcolegiado");
            $table->integer("codpersona_natural");
            $table->string("numero_colegiatura", 20)->nullable();
            $table->date("fecha_colegiatura")->nullable();
            $table->string("curriculum_vitae", 60)->nullable();
            $table->string("estado_colegiado", 1)->nullable()->comment("Determina el estado del colegiado Habilitado, Inhabilitado");
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
        Schema::dropIfExists('colegiado');
    }
}
