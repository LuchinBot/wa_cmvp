<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesobotonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acceso_boton', function (Blueprint $table) {
            $table->increments("codacceso_boton");
            $table->integer("codmodulos");
            $table->integer("codperfil");
            $table->integer("codboton");
            $table->integer("acceder")->default(1);
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
        Schema::dropIfExists('acceso_boton');
    }
}
