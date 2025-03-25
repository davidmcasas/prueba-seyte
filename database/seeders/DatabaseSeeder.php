<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = Role::query()->create([
            'code' => 'admin',
            'name' => 'Administrador',
        ]);

        $manager = Role::query()->create([
            'code' => 'manager',
            'name' => 'Encargado de Reservas',
        ]);

        $medic = Role::query()->create([
            'code' => 'medic',
            'name' => 'Médico',
        ]);

        User::factory()->create([
            'role_id' => $admin->id,
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'role_id' => $manager->id,
            'name' => 'Encargado',
            'email' => 'manager@example.com',
        ]);

        User::factory()->create([
            'role_id' => $medic->id,
            'name' => 'Médico',
            'email' => 'medic@example.com',
        ]);
    }
}
