<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTramiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite', function (Blueprint $table) {
            $table->increments("codtramite");
            $table->string("titulo", 250);
            $table->string("slug", 250)->nullable();
            $table->text("descripcion")->nullable();
            $table->integer("orden")->default(1);
            $table->string("icono", 60)->nullable();
            $table->string("derecho_pago")->nullable()->comment("Costo del tramite en texto o cadena");
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
        Schema::dropIfExists('tramite');
    }
}
