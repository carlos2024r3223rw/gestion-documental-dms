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
        User::updateOrCreate(
            ['email' => 'jefe@admin.com'],
            [
                'name' => 'Jefe Admin',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Usuario Normal
        User::updateOrCreate(
            ['email' => 'user@admin.com'],
            [
                'name' => 'Empleado Normal',
                'password' => bcrypt('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
