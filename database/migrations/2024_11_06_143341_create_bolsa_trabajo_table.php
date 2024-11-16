<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBolsaTrabajoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolsa_trabajo', function (Blueprint $table) {
            $table->increments("codbolsa_trabajo");
            $table->string("nombre_institucion", 250)->uniqid();
            $table->string("ruc_institucion", 11)->nullable();
            $table->text("url_referencial");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
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
        Schema::dropIfExists('bolsa_trabajo');
    }
}
