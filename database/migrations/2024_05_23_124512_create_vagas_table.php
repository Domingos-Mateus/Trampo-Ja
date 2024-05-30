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
        $table->unsignedInteger('profissional_id');
        $table->unsignedInteger('empresa_id');
        $table->timestamps();

        $table->foreign('profissional_id')->references('id')->on('profissionais')->onDelete('cascade');
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
