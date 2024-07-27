<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos
        Permission::create(['name' => 'administra el almacen']);
        Permission::create(['name' => 'aprueba y envia FUR']);
        Permission::create(['name' => 'recibe productos']);

        // Crear roles y asignar permisos
        $role = Role::create(['name' => 'Jefe de Área']);
        $role->givePermissionTo('aprueba y envia FUR');

        $role = Role::create(['name' => 'Jefe de Almacén']);
        $role->givePermissionTo('administra el almacen');

        $role = Role::create(['name' => 'Empleado de Almacén']);
        $role->givePermissionTo('recibe productos');
    }
}
