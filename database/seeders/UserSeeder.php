<?php

namespace Database\Seeders;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::Create([
            'name' => 'Admin',
            'email' => 'rulos01@gmail.com',
            'password' => bcrypt('0382646740Ju*'),
        ])->assignRole('Admin');

        User::Create([
            'name' => 'Evaluador 1',
            'email' => 'rulos02@gmail.com',
            'password' => bcrypt('0382646740Ju*'),
        ])->assignRole('Evaluador'); 

        // Bucle para crear 40 usuarios
        /* for ($i = 1; $i <= 40; $i++) {
            User::create([
                'name' => 'Evaluador ' . $i,
                'email' => 'evaluador' . $i . '@example.com', // Cambia el dominio según tus necesidades
                'password' => bcrypt('0382646740Ju*'), // Cambia 'contraseña' por la contraseña que desees
            ])->assignRole('Evaluador'); // Asigna el rol 'Evaluador' a cada usuario creado
        } */

        //User::factory(10)->create();
    }
}
