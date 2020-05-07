<?php

use Illuminate\Database\Seeder;
use App\ActionType;

class TypeActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActionType::create([
            'name_type_action'  => 'Nombramiento interno'
        ]);

        ActionType::create([
            'name_type_action'  => 'Amonestación verbal'
        ]);

        ActionType::create([
            'name_type_action'  => 'Tardanza'
        ]);

        ActionType::create([
            'name_type_action'  => 'Nombramiento con un mes de prueba'
        ]);

        ActionType::create([
            'name_type_action'  => 'Amonestación escrita'
        ]);

        ActionType::create([
            'name_type_action'  => 'Traslado'
        ]);

        ActionType::create([
            'name_type_action'  => 'Vacaciones anuales'
        ]);

        ActionType::create([
            'name_type_action'  => 'Capacitación'
        ]);

        ActionType::create([
            'name_type_action'  => 'Ascenso'
        ]);

        ActionType::create([
            'name_type_action'  => 'Indemnización'
        ]);

        ActionType::create([
            'name_type_action'  => 'Descuento de un día'
        ]);

        ActionType::create([
            'name_type_action'  => 'Ingreso'
        ]);

        ActionType::create([
            'name_type_action'  => 'Finalización de contrato sin responsabilidad para la empresa'
        ]);

        ActionType::create([
            'name_type_action'  => 'Falta grave'
        ]);

        ActionType::create([
            'name_type_action'  => 'Aumento de sueldo'
        ]);

        ActionType::create([
            'name_type_action'  => 'Incapacidades'
        ]);

        ActionType::create([
            'name_type_action'  => 'Permiso de minutos'
        ]);

        ActionType::create([
            'name_type_action'  => 'Bonificación'
        ]);
    }
}
