<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('param', function (Blueprint $table) {
            $table->string('codparam')->comment("Llave primaria para identificar parametros");
            $table->primary('codparam');
            $table->string('valor')->comment("Valor del parametro");
            $table->text('descripcion')->comment("Descripcion del parametro")->nullable();
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
        Schema::dropIfExists('param');
    }
}
