<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orden_servicio', function (Blueprint $table) {
            $table->boolean('finalizado')->default(false);
        });
    }

    public function down()
    {
        Schema::table('orden_servicio', function (Blueprint $table) {
            $table->dropColumn('finalizado');
        });
    }
};
