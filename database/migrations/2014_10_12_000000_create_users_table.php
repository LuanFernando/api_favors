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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('perfil_id');
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('data_nascimento');
            $table->string('cpf');
            $table->string('token');
            $table->date('data_criacao');
            $table->date('data_acesso');
            $table->date('data_perfil');
            $table->enum('status', ['Ativo', 'Inativo','Bloqueado','Em Analise']);
            $table->rememberToken();
            $table->string('firebase_token');

            //$table->foreign('perfil_id')->references('id')->on('perfils');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
