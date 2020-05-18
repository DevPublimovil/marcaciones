<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name_employee'         => $faker->firstNameMale,
        'surname_employee'      => $faker->lastName,
        'cod_marking'           => rand(63,163),
        'cod_terminal'          => '3213555',
        'user_id'               => rand(1,40),
        'salary'                => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1500),
        'type_employee'         => $faker->randomElement(['1','2']),
        'departament_id'        => rand(1,5),
        'company_id'            => rand(1,3)
    ];
});
