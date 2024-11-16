<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments("codusuario");
            $table->integer('codpersona_natural');
            $table->string('id_encrypt', 20)->unique()->nullable();
            $table->integer("codperfil");
            $table->string('usuario', 120)->nullable();
            $table->string('password', 200)->nullable();
            $table->rememberToken();
            $table->string('avatar', 60)->nullable();
            $table->string('baja', 1)->default("N");
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
        Schema::dropIfExists('usuario');
    }
}
