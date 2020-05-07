<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name_employee'         => $faker->firstNameMale,
        'surname_employee'      => $faker->lastName,
        'salary'                => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1500),
        'type_employee'         => $faker->randomElement(['1','2'])
    ];
});
