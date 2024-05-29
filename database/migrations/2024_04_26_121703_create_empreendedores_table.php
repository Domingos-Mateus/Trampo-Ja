<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpreendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empreendedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('nif');
            $table->integer('tipo_empreendedor');
            $table->string('foto')->nullable();
            $table->string('descricao_empreendimento')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('empreendedores');
    }
}
