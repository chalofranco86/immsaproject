<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orden_servicio', function (Blueprint $table) {
            $table->unsignedBigInteger('responsable')->nullable()->after('costo');
            $table->foreign('responsable')
                  ->references('id')
                  ->on('empleados')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orden_servicio', function (Blueprint $table) {
            $table->dropForeign(['responsable']);
            $table->dropColumn('responsable');
        });
    }
};