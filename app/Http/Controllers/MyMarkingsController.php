<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Employee;
use App\Marking;
use Carbon\Carbon as Fecha;
use Storage;
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
        $query = Marking::orderBy('markings.check_in', 'ASC')->MyMarkings($employee->cod_marking, $employee->cod_terminal)->whereBetween('created_at',[$first_day, $last_day])->get();

        //retorno una coleccion con los datos de sus marcaciones semanales
        return MyMarkings::collection($query);
    }

    /**
     * retorna todas las marcaciones mensuales de un empleado para un componente vue
     * 
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function showPeriod(Request $request, $id) 
    {
        //busco el empleado
        $employee = Employee::find($id);

        //establezco los filtros de mes
        $first_day = Fecha::parse($request->startDate)->format('Y-m-d');
        $last_day = Fecha::parse($request->endDate)->format('Y-m-d');


        //defino la consulta con sus filtros
        $query = Marking::orderBy('markings.check_in', 'ASC')->MyMarkings($employee->cod_marking, $employee->cod_terminal)->whereBetween('created_at',[$first_day, $last_day])->orderBy('created_at','ASC')->get();
        
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

        //establezco los filtros de mes
        $first_day = Fecha::now()->startOfWeek()->format('Y-m-d');
        $last_day = Fecha::now()->endOfWeek()->format('Y-m-d');

        //defino la consulta
        $registros = Marking::orderBy('markings.check_in', 'ASC')->MyMarkings($employee->cod_marking, $employee->cod_terminal)->whereBetween('created_at',[$first_day, $last_day])->get();

        $filtered_minutes = [];
        foreach ($registros as $key => $value) {
            if($value->late_arrivals != null)
            {
                $filtered_minutes[] = $value->late_arrivals;
            }
        }

        $minutes = $this->AddPlayTime($filtered_minutes);
        //si obtengo datos de la consulta realizo la operacion para obtener el porcentaje de llegadas tarde
        if($minutes){
            $percent = ($minutes / 0.30) * 100;

            $percent = ($percent > 100) ? 100 : $percent;
        }else{
            $percent = 0;
        }

        return round($percent,2);
    }

    public static function AddPlayTime($times) {
        $minutes = 0; 

        foreach ($times as $time) {
            list($hour, $minute) = explode('.', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;
        // returns the time already formatted
        return  sprintf('%02d.%02d', $hours, $minutes);
    }
}
