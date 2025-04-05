<?php

namespace Database\Seeders;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::Create([
            'name' => 'Juan Carlos Diaz Lara',
            'email' => 'rulos26@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Superadmin');
        User::Create([
            'name' => 'Juan Manuel Diaz Lara',
            'email' => 'rulos25@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Admin');

        User::Create([
            'name' => 'Juan Jose Diaz Betacourth',
            'email' => 'rulos24@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Cliente');

        // User::factory(10)->create();
    }
}
