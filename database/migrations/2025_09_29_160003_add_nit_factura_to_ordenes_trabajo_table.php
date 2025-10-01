<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->string('nit_factura', 20)->nullable()->after('serie_motor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->dropColumn('nit_factura');
        });
    }
};
