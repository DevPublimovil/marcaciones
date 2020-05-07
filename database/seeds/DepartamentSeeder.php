<?php

use Illuminate\Database\Seeder;
use App\Departament;

class DepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departament::create([
            'name'          => 'contabilidad',
            'display_name'  => 'Contabilidad'
        ]);

        Departament::create([
            'name'          => 'produccion',
            'display_name'  => 'Producción'
        ]);

        Departament::create([
            'name'          => 'finanzas',
            'display_name'  => 'Finanzas'
        ]);

        Departament::create([
            'name'          => 'proyectos',
            'display_name'  => 'Proyectos'
        ]);

        Departament::create([
            'name'          => 'bodega',
            'display_name'  => 'Bodega'
        ]);
    }
}
