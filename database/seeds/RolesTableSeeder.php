<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::create(['name' => 'empleado', 'display_name' => 'Empleado']);

        $role = Role::create(['name' => 'gerente', 'display_name' => 'Gerente']);

        $role = Role::create(['name' => 'rrhh', 'display_name' => 'RRHH']);

        $role = Role::create(['name' => 'subjefe', 'display_name' => 'Sub Jefe']);

        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('voyager::seeders.roles.admin'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'user']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('voyager::seeders.roles.user'),
            ])->save();
        }
    }
}
