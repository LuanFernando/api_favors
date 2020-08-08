<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign('perfil_id')->references('id')->on('perfils');
        });

        Schema::table('grupos_usuarios', function (Blueprint $table) {
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreign('grupo_id')->references('id')->on('grupos');
        });

        Schema::table('grupos_permissoes', function (Blueprint $table) {
            $table->foreign('permissao_id')->references('id')->on('permissoes');
            $table->foreign('grupo_id')->references('id')->on('grupos');
        });

        Schema::table('classificados', function (Blueprint $table) {
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
        
        Schema::table('config_categorias', function (Blueprint $table) {
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foreign_keys');
    }
}
