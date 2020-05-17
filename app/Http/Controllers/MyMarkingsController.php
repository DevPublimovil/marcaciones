<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Employee;
use App\Marking;
use Carbon\Carbon as Fecha;
use App\Http\Resources\MarkingsEmployee as MyMarkings;

class MyMarkingsController extends Controller
{
    /**
     * retorna todas las marcaciones semanales de un empleado para un componente vue
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showWeeklyDials($id){

        //busco el empleado
        $employee = Employee::find($id);

        //establezco los filtros de la semana
        $first_day = Fecha::now()->startOfWeek()->format('Y-m-d');
        $last_day   = Fecha::now()->endOfWeek()->format('Y-m-d');

        //defino la consulta con sus filtros
        $query = $employee->markings()->whereDate('markings.check_in','>=',$first_day)->whereDate('markings.check_out','<=',$last_day)->orderBy('markings.check_in', 'ASC')->get();

        //retorno una coleccion con los datos de sus marcaciones semanales
        return MyMarkings::collection($query);
    }

    /**
     * retorna todas las marcaciones mensuales de un empleado para un componente vue
     * 
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function showMonthDials($id) 
    {
        //busco el empleadoñ
        $employee = Employee::find($id);

        //establezco los filtros de mes
        $first_day = Fecha::now()->startOfMonth()->format('Y-m-d');
        $last_day = Fecha::now()->endOfMonth()->format('Y-m-d');

        //defino la consulta con sus filtros
        $query = $employee->markings()->whereDate('markings.check_in','>=',$first_day)->whereDate('markings.check_out','<=',$last_day)->orderBy('markings.check_in', 'ASC')->get();

        //retorno una coleccion de los datos de sus marcaciones mensuales
        return MyMarkings::collection($query);
    }

    /**
     * retorna el porcentaje de llegadas tardes para un empleado
     * @param int id
     * @return \Illuminate\Http\Response
     * 
     */
    public function showPercent($id)
    {
        //busco el empleado
        $employee = Employee::find($id);

        //defino la consulta
        $percent = $employee->markings()->sum('late_arrivals');

        //si obtengo datos de la consulta realizo la operacion para obtener el porcentaje de llegadas tarde
        if($percent){
            $percent = ($percent / 30) * 100;

            $percent = ($percent > 100) ? 100 : $percent;
        }else{
            $percent = 0;
        }

        return round($percent,2);
    }
}
