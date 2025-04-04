<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $evaluador = Role::create(['name' => 'Evaluador']);
        
        Permission::create(['name' => 'home'])->syncRoles([$admin, $evaluador]); //pagina principal
        
        /* permisos departamentos */
        Permission::create(['name' => 'departamentos.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.store'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.show'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.update'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.destroy'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.edit'])->syncRoles([$admin]);
        /* permisos municipios */
        Permission::create(['name' => 'municipios.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'municipios.store'])->syncRoles([$admin]);
        Permission::create(['name' => 'municipios.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'municipios.show'])->syncRoles([$admin]);
        Permission::create(['name' => 'municipios.update'])->syncRoles([$admin]);
        Permission::create(['name' => 'municipios.destroy'])->syncRoles([$admin]);
        Permission::create(['name' => 'municipios.edit'])->syncRoles([$admin]);


        /* perimsos fin reporte */
        Permission::create(['name' => 'informes.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'informes.store'])->syncRoles([$admin]);
        Permission::create(['name' => 'informes.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'informes.show'])->syncRoles([$admin]);
        Permission::create(['name' => 'informes.update'])->syncRoles([$admin]);
        Permission::create(['name' => 'informes.destroy'])->syncRoles([$admin]);
        Permission::create(['name' => 'informes.edit'])->syncRoles([$admin]);

        //permiso reporte
        Permission::create(['name' => 'reporte.carta'])->syncRoles([$admin]);
        
    }
}
