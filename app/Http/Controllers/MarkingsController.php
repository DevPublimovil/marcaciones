<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Marking;
use App\Http\Resources\EmployeeResource;
use Auth;
use Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\DB;

class MarkingsController extends Controller
{

    /**
     * display a listing of the resource
     * @return \Illuminate\Http\Response
     */

     public function index()
     {
        $columns = Employee::$columns;
        return view('markings.index', compact('columns'));
     }

    /**
     * retorna todas las marcaciones junto con sus empleados
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAllMarkings(Request $request)
    {
        
        

      /*   'hoursworked' => $this->markings()->whereBetween('check_in',[$start_week, $end_week])->sum('hours_worked'),
                'extrahours' => $this->markings()->whereBetween('check_in',[$start_week, $end_week])->sum('extra_hours'),
                'latearrivals' => $this->markings()->whereBetween('check_in',[$start_week, $end_week])->sum('late_arrivals'), */
    }

    /**
     * retorna los calculos de horas trabajadas y llegadas tarde
     * 
     * @param $id int
     * @return \Illuminate\Http\Response
     */

     public function calcHoursWeekly($id)
     {
         //buscar empleado
         $employee = Employee::find($id);

         //establezco el filtro
         $first_day = Fecha::now()->startOfWeek()->format('Y-m-d');
         $last_day = Fecha::now()->endOfWeek()->format('Y-m-d');

        $query = $employee->markings()->where('check_in','>=',$first_day)->where('check_out','<=',$last_day);

         //total horas trabajadas
        $hours_worked = $query->sum('hours_Worked');
        //total horas extras
        $extra_hours = $query->sum('extra_hours');
        //total minutos acumulados
        $late_arrivals = $query->sum('late_arrivals');

         return response()->json([
             'hours_worked' => $hours_worked,
             'extra_hours'  => $extra_hours,
             'late_arrivals'   => $late_arrivals,
         ],200);
     }

     
}
