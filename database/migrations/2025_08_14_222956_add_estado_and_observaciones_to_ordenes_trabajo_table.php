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
            $table->enum('estado', ['Recibido', 'Revisión', 'Autorizado', 'Entregado'])
                ->default('Recibido');
            $table->text('observaciones')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->dropColumn('observaciones');
        });
    }
};
