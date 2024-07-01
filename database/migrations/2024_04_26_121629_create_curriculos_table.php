<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('distrito');
            $table->string('profissao')->nullable();
            $table->string('cidade');
            $table->string('bairro');
            $table->string('foto')->nullable();
            $table->string('video')->nullable();
            $table->string('minimo_hora')->nullable();
            $table->string('faixa_etaria');
            $table->string('escolaridade');
            $table->string('instituicao_ensino')->nullable();
            $table->string('sobre')->nullable();
            $table->string('habilidades')->nullable();
            $table->string('codigo_postal');
            $table->string('ligacao')->nullable();
            $table->integer('total_views')->default(0);
            $table->string('formacao_academica')->nullable();
            $table->string('anos_experiencia_profissional')->nullable();
            $table->integer('candidatos_id')->unsigned();
            $table->boolean('permitir_foto')->default(1);
            $table->boolean('permitir_faixa_etaria')->default(1);
            $table->boolean('permitir_escolaridade')->default(1);
            $table->boolean('permitir_video')->default(1);
            $table->integer('status')->defalt(1);


            $table->foreign('candidatos_id')->references('id')->on('candidatos');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculos');
    }
}
