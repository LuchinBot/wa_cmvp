<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_caja', function (Blueprint $table) {
            $table->increments('codtipo_caja');
            $table->string('descripcion', 50);
            $table->string('ver_en_recibo_ingreso', 1)->default("N");
            $table->string('ver_en_recibo_egreso', 1)->default("N");
            $table->string('ver_en_adquisicion', 1)->default("N");
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
        Schema::dropIfExists('tipo_caja');
    }
}
