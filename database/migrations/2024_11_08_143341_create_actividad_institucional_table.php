<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadInstitucionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_institucional', function (Blueprint $table) {
            $table->increments("codactividad_institucional");
            $table->string("titulo", 250)->uniqid();
            $table->string("slug", 250);
            $table->date("fecha");
            $table->string("imagen_principal", 60)->nullable();
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
        Schema::dropIfExists('actividad_institucional');
    }
}
