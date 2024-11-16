<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_natural', function (Blueprint $table) {
            $table->increments("codpersona_natural");
            $table->integer("codtipo_documento_identidad");
            $table->integer("codsexo")->nullable();
            $table->integer("codestado_civil")->nullable();
            $table->string("id_ubigeo", 15)->nullable();
            $table->string("cod_dpto", 5)->nullable();
            $table->string("cod_prov", 5)->nullable();
            $table->string("cod_dist", 5)->nullable();
            $table->string('nombres', 120);
            $table->string('apellido_paterno', 40);
            $table->string('apellido_materno', 40);
            $table->string("numero_documento_identidad", 16);
            $table->date("fecha_emision_documento_identidad")->nullable();
            $table->string("codigo_verificacion_documento_identidad", 5)->nullable();
            $table->text('direccion')->nullable();
            $table->string("telefono_movil", 80)->nullable();
            $table->string("email", 90)->nullable();
            $table->date("fecha_nacimiento")->nullable();
            $table->string("foto", 80)->nullable();
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
        Schema::dropIfExists('persona');
    }
}
