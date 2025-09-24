<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->date('fecha_fin')->nullable()->after('fecha_entrega');
        });
    }

    public function down()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->dropColumn('fecha_fin');
        });
    }
};
