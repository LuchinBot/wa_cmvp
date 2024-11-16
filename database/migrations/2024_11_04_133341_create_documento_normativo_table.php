<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentoNormativoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_normativo', function (Blueprint $table) {
            $table->increments("coddocumento_normativo");
            $table->string("titulo", 250);
            $table->string("archivo", 60)->nullable();
            $table->integer("orden")->default(0);
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
        Schema::dropIfExists('documento_normativo');
    }
}
