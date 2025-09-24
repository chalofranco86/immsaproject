<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Agregar el campo responsable como unsignedBigInteger (para claves foráneas)
            $table->unsignedBigInteger('responsable')->nullable()->after('empleado_id');
            
            // Definir la relación de clave foránea
            $table->foreign('responsable')
                  ->references('id')
                  ->on('empleados')
                  ->onDelete('set null'); // o 'cascade' según tus necesidades
        });
    }

    public function down()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Eliminar la relación de clave foránea primero
            $table->dropForeign(['responsable']);
            
            // Eliminar la columna
            $table->dropColumn('responsable');
        });
    }
};