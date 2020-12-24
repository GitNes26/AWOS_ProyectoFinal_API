<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroSensores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_sensores', function (Blueprint $table) {
            $table->id();
            // $table->double('humedad',2,1); // tomar temperatura ambiente → '30'%
            // $table->double('temperatura',3,2); // tomar temperatura ambiente → '37' °C
            // $table->double('ultrasonico',2,2); // indica la cantidad de croquetas en el dispensador → quedan '500'g = 25%
            $table->string('humedad'); // tomar temperatura ambiente → '30'%
            $table->string('temperatura'); // tomar temperatura ambiente → '37' °C
            $table->string('ultrasonico'); // indica la cantidad de croquetas en el dispensador → quedan 
            $table->string('fotoresistencia')->nullable(); // indica si el tazon tiene croquetas → lleno / rellenar
            $table->string('pir'); // indica cuantas veces se acerco el perro a comer → '#'
            $table->string('boton')->nullable(); // indica cuando el usuario relleno por medio de la app
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('registro_sensores');
    }
}
