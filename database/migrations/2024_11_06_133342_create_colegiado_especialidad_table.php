<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColegiadoEspecialidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colegiado_especialidad', function (Blueprint $table) {
            $table->increments("codcolegiado_especialidad");
            $table->integer("codcolegiado");
            $table->integer("codespecialidad");
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
        Schema::dropIfExists('colegiado_especialidad');
    }
}
