<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso', function (Blueprint $table) {
            $table->increments("codcurso");
            $table->integer("codtipo_curso");
            $table->string("titulo", 250);
            $table->string("slug", 250)->nullable();
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("descripcion")->nullable();
            $table->integer("horas_lectivas")->default(0)->comment("Cantidad de horas lectivas");
            $table->string("imagen_flayer", 60)->nullable();
            $table->string("con_certificado", 1)->default("N")->comment("Indica si el curso tiene o no certificado");
            $table->string("plantilla_certificado", 60)->nullable()->comment("Imagen de fondo para el certificado");
            $table->integer("codcolegiado_vicedecano")->nullable();
            $table->integer("codcolegiado_director")->nullable();
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
