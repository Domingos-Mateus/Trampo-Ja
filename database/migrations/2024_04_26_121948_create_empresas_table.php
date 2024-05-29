<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empreendedor_id')->unsigned();
            $table->string('nome');
            $table->string('foto')->nullable();
            $table->string('video')->nullable();
            $table->integer('ano_criacao');
            $table->string('link_website')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('tamanho_empresa')->nullable();
            $table->string('descricao_curta');
            $table->string('descricao_longa')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->foreign('empreendedor_id')->references('id')->on('empreendedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
