<?php

use Illuminate\Database\Seeder;
use App\EmployeeType;

class TypeEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeType::create([
            'name_type_employee' => 'administrativo'
        ]);

        EmployeeType::create([
            'name_type_employee' => 'operativo'
        ]);
    }
}
