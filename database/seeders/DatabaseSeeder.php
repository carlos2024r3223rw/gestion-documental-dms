<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador (Jefe)
        User::factory()->create([
            'name' => 'Jefe Admin',
            'email' => 'jefe@admin.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        // Usuario Normal
        User::factory()->create([
            'name' => 'Empleado Normal',
            'email' => 'user@admin.com',
            'password' => bcrypt('password123'),
            'role' => 'user'
        ]);
    }
}
