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
        Schema::table('propietarios', function (Blueprint $table) {
            $table->string('nit')->nullable()->after('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('propietarios', function (Blueprint $table) {
            $table->dropColumn('nit');
        });
    }
};
