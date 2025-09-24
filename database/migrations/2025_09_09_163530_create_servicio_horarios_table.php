<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicio_horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_trabajo_id');
            $table->unsignedBigInteger('servicio_id');
            $table->string('color')->nullable();
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            $table->foreign('orden_trabajo_id')->references('id')->on('ordenes_trabajo')->onDelete('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicio_horarios');
    }
};
