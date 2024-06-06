<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVagasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('vagas', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('empresa_id');
        $table->string('titulo');
        $table->text('descricao');
        $table->text('requisitos');
        $table->decimal('salario', 10, 2)->nullable();
        $table->string('localizacao')->nullable();
        $table->integer('dias_disponiveis')->nullable();
        $table->date('data_expiracao')->nullable();
        $table->timestamps();

        $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vagas');
    }
}
