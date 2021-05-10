<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('NOMBRE_GUANXE')->nullable(true);
            $table->string('NOMBRE_COMERCIAL')->nullable(true);
            $table->string('REFERENCIA_COMERCIAL')->nullable(true);
            $table->string('REFERENCIA')->nullable(true);
            $table->string('REFERENCIA_COMBINACION')->nullable(true);
            $table->string('TIPO')->nullable(true);
            $table->string('GENERO')->nullable(true);
            $table->string('COLOR')->nullable(true);
            $table->string('TALLA')->nullable(true);
            $table->string('STOCK')->nullable(true);
            $table->string('PRECIO_BASE')->nullable(true);
            $table->string('DESCRIPCION_LARGA')->nullable(true);
            $table->string('DESCRIPCION_CORTA')->nullable(true);
            $table->string('CATEGORIA')->nullable(true);
            $table->string('CATEGORIA_POR_DEFECTO')->nullable(true);
            $table->string('MARCA_FABRICANTE')->nullable(true);
            $table->string('RUTA_IMAGENES')->nullable(true);
            $table->string('ESTADO')->nullable(true);
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
        Schema::dropIfExists('articulos');
    }
}
