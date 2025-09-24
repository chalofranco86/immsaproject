<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear un empleado admin si no existe
        $empleado = Empleado::firstOrCreate(
            ['nombre' => 'Administrador'],
            ['puesto' => 'Administrador']
        );

        // Crear usuario admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'empleado_id' => $empleado->id,
                'name' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );
    }
}