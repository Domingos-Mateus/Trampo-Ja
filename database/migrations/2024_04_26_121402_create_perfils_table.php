<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfils', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('usuario_id')->nullable();
            $table->integer('profissional_id')->nullable();
            $table->integer('servico_id')->nullable();
            $table->string('nome_usuario');
            $table->string('idade');
            $table->string('telefone');
            $table->string('foto');
            $table->string('descricao');
             $table->integer('provincia_id')->nullable();
            $table->integer('cidade_id')->nullable();
            $table->integer('bairro_id')->nullable();
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
        Schema::dropIfExists('perfils');
    }
}
