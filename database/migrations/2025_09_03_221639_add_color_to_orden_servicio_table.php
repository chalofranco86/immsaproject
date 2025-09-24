<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orden_servicio', function (Blueprint $table) {
            $table->string('color')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('orden_servicio', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
