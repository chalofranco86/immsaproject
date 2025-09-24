<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Eliminar la clave foránea primero
            $table->dropForeign(['responsable']);
            
            // Eliminar la columna
            $table->dropColumn('responsable');
        });
    }

    public function down()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Recrear la columna (para revertir la eliminación)
            $table->unsignedBigInteger('responsable')->nullable()->after('empleado_id');
            
            // Recrear la clave foránea
            $table->foreign('responsable')
                  ->references('id')
                  ->on('empleados')
                  ->onDelete('set null');
        });
    }
};