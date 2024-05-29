<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagensServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens_servicos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
            $table->string('imagem');
            $table->integer('profissional_id')->unsigned();
            $table->integer('servico_id')->unsigned();

            $table->foreign('profissional_id')->references('id')->on('profissionais');
            $table->foreign('servico_id')->references('id')->on('servicos');
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
        Schema::dropIfExists('imagens_servicos');
    }
}
