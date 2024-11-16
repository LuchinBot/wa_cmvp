<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipodocumentoidentidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_documento_identidad', function (Blueprint $table) {
            $table->increments("codtipo_documento_identidad");
            $table->string('descripcion', 120);
            $table->string('abreviatura', 40)->nullable();
            $table->integer('longitud')->nullable();
            $table->string('id_api', 10)->nullable();
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
        Schema::dropIfExists('tipo_documento_identidad');
    }
}
