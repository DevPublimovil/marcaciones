<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(EmployeeSeeder::class);
        //$this->call(UserTableSeeder::class);
        //$this->call(CountrySeeder::class);
        //$this->call(CompanySeeder::class);
        //$this->call(DepartamentSeeder::class);
        //$this->call(VoyagerDatabaseSeeder::class);
        $this->call(TypeActionsSeeder::class);
        
    }
}
