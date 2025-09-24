<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->onDelete('cascade');
            $table->string('role')->default('empleado'); // Puedes usar el puesto del empleado o un rol separado
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empleado_id']);
            $table->dropColumn(['empleado_id', 'role']);
        });
    }
};