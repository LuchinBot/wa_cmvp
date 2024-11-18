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
            $table->string('nombre', 50);
            $table->char('ver_en_recibo_ingreso', 10);
            $table->char('ver_en_recibo_egreso', 10);
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
