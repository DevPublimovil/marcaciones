<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name'          => 'El Salvador',
            'image_flag'    => 'countries/sv.png'
        ]);

        Country::create([
            'name'          => 'Guatemala',
            'image_flag'    => 'countries/gt.png'
        ]);

        Country::create([
            'name'          => 'Honduras',
            'image_flag'    => 'countries/hn.png'
        ]);

        Country::create([
            'name'          => 'Nicaragua',
            'image_flag'    => 'countries/nic.png'
        ]);

        Country::create([
            'name'          => 'Costa Rica',
            'image_flag'    => 'countries/cr.png'
        ]);

        Country::create([
            'name'          => 'Panama',
            'image_flag'    => 'countries/pan.png'
        ]);
    }
}
