<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipanteCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participante_curso', function (Blueprint $table) {
            $table->increments("codparticipante_curso");
            $table->integer("codcurso");
            $table->integer("codparticipante");
            $table->string("pagado", 1)->default("N")->comment("Indica si el participante canceló o pagó el curso para su certificado");
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
        Schema::dropIfExists('curso');
    }
}
