<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('es_ES');
        
        $users = \App\Models\User::factory(5)->create(['role' => 'user']);
        
        $statuses = ['draft', 'pending', 'approved', 'rejected'];
        $titles = ['Contrato de Arrendamiento', 'Minuta de Reunión Directiva', 'Política de Privacidad V2', 'Acuerdo de Confidencialidad (NDA)', 'Reporte Financiero Q3'];

        foreach ($titles as $index => $title) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];

            $doc = \App\Models\Document::create([
                'title' => $title . ' 2026',
                'description' => $faker->paragraph(),
                'user_id' => $user->id,
                'status' => $status,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Versión 1.0
            $doc->versions()->create([
                'user_id' => $user->id,
                'file_path' => 'demo/dummy.pdf', // Archivo falso
                'original_name' => 'documento_v1.pdf',
                'version' => '1.0',
                'change_summary' => 'Carga inicial del documento.',
                'created_at' => $doc->created_at,
            ]);

            // Algunos tienen versión 2.0
            if (rand(0, 1)) {
                $doc->versions()->create([
                    'user_id' => $user->id,
                    'file_path' => 'demo/dummy_v2.pdf',
                    'original_name' => 'documento_final_v2.pdf',
                    'version' => '2.0',
                    'change_summary' => 'Se corrigieron observaciones legales.',
                    'created_at' => now()->subDays(rand(1, 5)),
                ]);
            }
        }
    }
}
