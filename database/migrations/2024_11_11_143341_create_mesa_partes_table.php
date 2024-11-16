<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesaPartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesa_partes', function (Blueprint $table) {
            $table->increments("codmesa_partes");
            $table->integer("codpersona_natural_remitente");
            $table->string("asunto", 250)->nullable();
            $table->string("email_remitente", 180)->nullable();
            $table->text("contenido")->nullable();
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
        Schema::dropIfExists('mesa_partes');
    }
}
