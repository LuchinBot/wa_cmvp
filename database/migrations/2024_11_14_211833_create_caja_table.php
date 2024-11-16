<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->increments("codcaja");
            $table->integer("codusuario");
            $table->integer('idtipo_caja');
            $table->date('fecha_apertura');
            $table->date('fecha_cierra')->nullable();
            $table->decimal('monto_apertura', 10, 2);
            $table->decimal('monto_cierre', 10, 2)->nullable();
            $table->integer('estado');
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
        Schema::dropIfExists('caja');
    }
}
