<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePronunciamientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pronunciamiento', function (Blueprint $table) {
            $table->increments("codpronunciamiento");
            $table->string("titulo", 250);
            $table->string("slug", 250)->nullable()->unique();
            $table->text("descripcion")->nullable();
            $table->string("imagen", 60)->nullable();
            $table->string("imagen_flayer", 60)->nullable();
            $table->date("fecha");
            $table->date("fecha_publicacion_inicio");
            $table->date("fecha_publicacion_fin");
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
        Schema::dropIfExists('pronunciamiento');
    }
}
