<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boton', function (Blueprint $table) {
            $table->increments("codboton");
            $table->string("descripcion", 100);
            $table->string("icono", 80)->nullable();
            $table->string("id_name", 80);
            $table->string("clase_name", 80);
            $table->string("atajo", 20)->nullable();
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
        Schema::dropIfExists('boton');
    }
}
