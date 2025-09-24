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
    Schema::create('orden_servicio', function (Blueprint $table) {
        $table->id();
        $table->foreignId('orden_trabajo_id')->constrained('ordenes_trabajo');
        $table->foreignId('servicio_id')->constrained();
        $table->decimal('costo', 8, 2);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_servicio');
    }
};
