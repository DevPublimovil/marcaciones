<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Marking;
use App\CompanyResource;
use Auth;
use DataTables;
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
        return view('markings.index');
     }

    /**
     * retorna todas las marcaciones junto con sus empleados
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAllMarkings()
    {
        $model = Employee::SearchPaginateAndOrder();
        $columns = Employee::$columns;

        return response()->json([
            'model' => $model,
            'columns'   => $columns
        ]);
        /* $user = User::find(Auth::id());
        $resource = $user->companiesResources()->orderBy('id','ASC')->first();

        $query = Employee::where('company_id',$resource->company_id)->with(['user','departament:id,display_name','company:id,display_name'])->orderBy('surname_employee','ASC')->get();

        return DataTables::of($query)
        ->addColumn('actions', function($row){
            $profile = $row->markings()->sum('hours_worked');
            return view('partials.info', compact('profile'));
        })
        ->make(true); */
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
