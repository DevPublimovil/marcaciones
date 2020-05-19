<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Marking;
use App\Http\Resources\EmployeeResource;
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
        $column = $this->selectColumn($request->column);
        $searchcolumn = $this->selectColumn($request->search_column);
        $model = Employee::SearchPaginateAndOrder($column, $searchcolumn);

        $query = EmployeeResource::collection($model);
        
        return $query;
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

     public function selectColumn($column)
     {
        switch ($column) {
            case 'apellidos':
                return 'surname_employee';
                break;
            case 'codigo':
                return 'cod_marking';
                break;
            case 'tipo':
                return 'type_employee';
                break;
            case 'compañia':
                return 'company_id';
                break;
            case 'departamento':
                return 'departament_id';
                break;
            case 'codigo':
                return 'cod_marking';
                break;
            
            default:
                return 'name_employee';
                break;
        }
     }
}
