<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitoTramiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisito_tramite', function (Blueprint $table) {
            $table->increments("codrequisito_tramite");
            $table->integer("codtramite");
            $table->integer("codrequisito");
            $table->string("nota", 250)->nullable()->comment("Nota adicional del requisito para el tramite relacionado");
            $table->string("archivo", 60)->nullable()->comment("Archivo en formato PDF del requisito, no todos los requisitos tienen archivos subidos");
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
        Schema::dropIfExists('requisito_tramite');
    }
}
