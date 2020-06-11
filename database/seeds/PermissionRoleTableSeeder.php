<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        $role = Role::where('name', 'empleado')->firstOrFail();

        $role->permissions()->sync([7,8,9]);

        $role = Role::where('name', 'gerente')->firstOrFail();

        $role->permissions()->sync([6,7,8,9,10,11,12,13,14]);

        $role = Role::where('name', 'rrhh')->firstOrFail();

        $role->permissions()->sync([6,7,8,9,10,11,12,13,14]);

    }
}
