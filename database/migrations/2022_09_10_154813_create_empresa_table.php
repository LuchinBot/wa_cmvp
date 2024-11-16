<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments("codempresa");
            $table->string("id_ubigeo", 15)->nullable();
            $table->string("razon_social", 150);
            $table->string("abreviatura", 80)->nullable();
            $table->string("ruc", 11);
            $table->string("telefonos", 120)->nullable();
            $table->string("email", 120)->nullable();
            $table->text("direccion")->nullable();
            $table->string("logo", 60)->nullable();
            $table->text("longitud")->nullable();
            $table->text("latitud")->nullable();
            $table->text("horario_atencion")->nullable();
            $table->text("token")->nullable();
            $table->text("pagina_web")->nullable();
            $table->text("objetivo")->nullable();
            $table->text("imagen_objetivo")->nullable();
            $table->text("historia")->nullable();
            $table->text("mision")->nullable();
            $table->text("vision")->nullable();
            $table->text("descripcion_consejo")->nullable();
            $table->string("imagen_consejo", 60)->nullable();
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
        Schema::dropIfExists('empresa');
    }
}
