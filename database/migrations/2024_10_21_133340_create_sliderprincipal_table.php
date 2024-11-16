<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderprincipalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_principal', function (Blueprint $table) {
            $table->increments("codslider_principal");
            $table->string("titulo", 250);
            $table->string("subtitulo", 220)->nullable();
            $table->string("imagen", 40)->nullable();
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
        Schema::dropIfExists('slider_principal');
    }
}
