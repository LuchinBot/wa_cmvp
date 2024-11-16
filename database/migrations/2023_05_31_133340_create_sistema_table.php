<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sistema', function (Blueprint $table) {
            $table->increments("codsistema");
            $table->string("identificador", 10)->nullable()->uniqid();
            $table->string("descripcion", 80);
            $table->string("abreviatura", 60)->nullable();
            $table->text("url")->nullable();
            $table->text("contenido")->nullable();
            $table->string("icono", 40)->nullable();
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
        Schema::dropIfExists('sistema');
    }
}
