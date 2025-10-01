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
        // Cambiar el tipo de dato del campo 'estado' para incluir 'Reclamo'
        DB::statement("ALTER TABLE ordenes_trabajo MODIFY estado ENUM('Recibido', 'Revisión', 'Autorizado', 'Entregado', 'Reclamo', 'No Autorizado', 'Crédito') NOT NULL DEFAULT 'Recibido'");
    }

    public function down()
    {
        // Revertir el cambio (opcional)
        DB::statement("ALTER TABLE ordenes_trabajo MODIFY estado ENUM('Recibido', 'Revisión', 'Autorizado', 'Entregado') NOT NULL DEFAULT 'Recibido'");
    }
};
