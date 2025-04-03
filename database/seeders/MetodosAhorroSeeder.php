<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodosAhorroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metodos_ahorro')->insert([
            [
                'nombre' => '50/30/20',
                'descripcion' => 'Divide los ingresos en 50% necesidades, 30% deseos y 20% ahorro.',
                'porcentajes' => json_encode(['necesidades' => 50, 'deseos' => 30, 'ahorro' => 20]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Método Caramelo',
                'descripcion' => 'Distribuye el dinero en 5 partes iguales para distintas áreas de la vida.',
                'porcentajes' => json_encode(['necesidades' => 50, 'educacion' => 10, 'diversion' => 10, 'inversion' => 10, 'ahorro' => 10]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Método Pirámide',
                'descripcion' => 'Prioriza gastos desde necesidades hasta ahorro a largo plazo.',
                'porcentajes' => json_encode(['base' => 60, 'medio' => 15, 'cima' => 5]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
