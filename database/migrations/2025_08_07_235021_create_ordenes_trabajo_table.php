<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
        $table->id();
        $table->string('numero_orden')->unique();
        $table->foreignId('propietario_id')->constrained();
        $table->foreignId('empleado_id')->constrained('empleados');
        $table->date('fecha_recibido');
        $table->date('fecha_entrega')->nullable();
        $table->decimal('subtotal', 8, 2);
        $table->decimal('descuento', 8, 2);
        $table->decimal('total', 8, 2);
        $table->decimal('anticipo', 8, 2);
        $table->decimal('saldo', 8, 2);
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};
