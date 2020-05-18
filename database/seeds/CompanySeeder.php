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
            'name'          => 'publimovil sv',
            'display_name'  => 'Publimovil',
            'country_id'    => '1'
        ]);

        Company::create([
            'name'          => 'publimagen sv',
            'display_name'  => 'Publimagen',
            'country_id'    => '1'
        ]);

        Company::create([
            'name'          => 'urbman sv',
            'display_name'  => 'Urbman',
            'country_id'    => '1'
        ]);
    }
}
