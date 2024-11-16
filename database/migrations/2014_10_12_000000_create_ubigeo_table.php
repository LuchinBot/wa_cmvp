<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbigeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubigeo', function (Blueprint $table) {
            $table->increments("id_ubigeo");
            $table->string('cod_dpto', 2);
            $table->string('cod_prov', 2);
            $table->string('cod_dist', 2);
            $table->string('codccpp', 4)->nullable();
            $table->string('nombre', 200);
            $table->string('reniec', 10)->nullable();
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
        Schema::dropIfExists('ubigeo');
    }
}
