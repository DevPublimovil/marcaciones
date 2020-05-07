<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name'          => 'publimovil',
            'display_name'  => 'Publimovil',
        ]);

        Company::create([
            'name'          => 'publimagen',
            'display_name'  => 'Publimagen',
        ]);

        Company::create([
            'name'          => 'urbman',
            'display_name'  => 'Urbman',
        ]);
    }
}
