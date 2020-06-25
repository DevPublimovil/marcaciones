<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        Menu::firstOrCreate([
            'name' => 'admin',
        ]);

        Menu::firstOrCreate([
            'name' => 'manager',
        ]);

        Menu::firstOrCreate([
            'name' => 'rrhh',
        ]);

        Menu::firstOrCreate([
            'name' => 'employee',
        ]);

        Menu::firstOrCreate([
            'name' => 'subjefe',
        ]);
    }
}
