<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->increments("codmodulos");
            $table->integer("codpadre")->nullable();
            $table->integer("codsistema");
            $table->string("descripcion", 180);
            $table->string("abreviatura", 60)->nullable();
            $table->text("url")->nullable();
            $table->string("icono", 40)->nullable();
            $table->integer("orden")->default(1);
            $table->string("acceso_directo", 1)->default('N');
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
        Schema::dropIfExists('modulos');
    }
}
