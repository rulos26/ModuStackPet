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
        // Usuario root con acceso completo
        $root = User::firstOrCreate(
            ['email' => 'root@modustackpet.com'],
            [
                'name' => 'root',
                'password' => bcrypt('root'),
                'email_verified_at' => now(),
                'activo' => true,
            ]
        );
        if (!$root->hasRole('Superadmin')) {
            $root->assignRole('Superadmin');
        }

        // Usuario Superadmin
        $superadmin = User::firstOrCreate(
            ['email' => 'rulos26@gmail.com'],
            [
                'name' => 'Juan Carlos Diaz Lara',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$superadmin->hasRole('Superadmin')) {
            $superadmin->assignRole('Superadmin');
        }

        // Usuario Admin
        $admin = User::firstOrCreate(
            ['email' => 'rulos25@gmail.com'],
            [
                'name' => 'Juan Manuel Diaz Lara',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        // Usuario Cliente
        $cliente = User::firstOrCreate(
            ['email' => 'rulos24@gmail.com'],
            [
                'name' => 'Juan Jose Diaz Betacourth',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$cliente->hasRole('Cliente')) {
            $cliente->assignRole('Cliente');
        }

        // Usuario Paseador
        $paseador = User::firstOrCreate(
            ['email' => 'rulos23@gmail.com'],
            [
                'name' => 'Jose Antonio Aguilar',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$paseador->hasRole('Paseador')) {
            $paseador->assignRole('Paseador');
        }

        // Crear usuarios de prueba solo si no existen muchos usuarios
        $existingUsersCount = User::count();
        if ($existingUsersCount < 15) {
            $roles = ['Superadmin', 'Admin', 'Cliente', 'Paseador'];
            $usersToCreate = 15 - $existingUsersCount;
            
            User::factory($usersToCreate)->create()->each(function ($user) use ($roles) {
                $user->assignRole($roles[array_rand($roles)]);
            });
        }
    }
}
