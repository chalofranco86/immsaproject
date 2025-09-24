<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('servicio_horarios', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fin')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('servicio_horarios', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable(false)->change();
            $table->time('hora_fin')->nullable(false)->change();
        });
    }
};
