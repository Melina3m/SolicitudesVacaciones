<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear el usuario Administrador
        User::create([
            'name' => 'Admin Vacaciones',
            'email' => 'admin@prueba.com',
            'password' => Hash::make('password0828'),
            'role' => 'admin',
            'position' => 'Gerente de Recursos Humanos',
            'entry_date' => '2022-01-15',
            'status' => 'activo',
        ]);

        // 2. Crear el usuario Supervisor
        $supervisor = User::create([
            'name' => 'Supervisor Carlos',
            'email' => 'supervisor@prueba.com',
            'password' => Hash::make('password0828'),
            'role' => 'supervisor',
            'position' => 'Líder de Desarrollo',
            'entry_date' => '2023-03-10',
            'status' => 'activo',
        ]);

        // 3. Crear el Empleado y asignarle el ID del supervisor que acabamos de crear
        User::create([
            'name' => 'Empleado Juan',
            'email' => 'empleado@prueba.com',
            'password' => Hash::make('password0828'),
            'role' => 'empleado',
            'position' => 'Desarrollador Fullstack',
            'entry_date' => '2025-05-01',
            'supervisor_id' => $supervisor->id,
            'status' => 'activo',
        ]);
    }
}