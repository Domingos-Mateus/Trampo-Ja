<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('servico_id')->nullable();
            $table->integer('provincia_id')->nullable();
            $table->integer('cidade_id')->nullable();
            $table->integer('bairro_id')->nullable();
            $table->string('foto_perfil')->nullable();
            $table->string('telefone')->nullable();
            $table->integer('usuario_id')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profissionais');
    }
}
