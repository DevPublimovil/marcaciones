<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Marking;
use App\Http\Resources\EmployeeResource;
use Auth;
use Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\DB;

class EmployeeJsonController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $column = $this->selectColumn($request->column);
        if($user->role->name == 'gerente' || $user->role->name == 'subjefe')
        {
            $resource =  $user->workersGte()->where('status',1);
        }else{
            $resource = $user->appcompany->company->employees()->where('status',1);
        }

        (!$request->time) ? $timestable = $resource->first() : $timestableTwo = $request->time;

        $model = $resource->where('timetable_id', $timestable->timetable_id ?? $timestableTwo ?? null)->orderBy($column, $request->direction)
                ->where(function($query) use($request){
                    if($request->search_input)
                    {
                        return $query->where('name_employee', 'LIKE', '%' . $request->search_input . '%')
                            ->orWhere('surname_employee', 'LIKE', '%' . $request->search_input . '%')
                            ->orWhere('employees.cod_marking', 'LIKE', '%' . $request->search_input . '%');
                    }
                })->paginate($request->per_page);

        return  EmployeeResource::collection($model);

    }

    public function markings(Request $request, $id)
    {
        $start_date = Fecha::parse($request->start_date)->format('Y-m-d');
        $end_date = Fecha::parse($request->end_date)->format('Y-m-d');

        $employee = Employee::find($id);
        $markings = $employee->markings()->whereDate('check_in','>=',$start_date)->whereDate('check_in','<',$end_date);
        return response()->json([
            'hours_worked'  => $markings->sum('hours_worked'),
            'extra_hours'   => $markings->sum('extra_hours'),
            'late_arrivals' => $markings->sum('late_arrivals'),
            'time' => $end_date
        ]);
    }

    public function showEmployees(){

        $user = User::find(Auth::id());
        if($user->role->name == 'gerente' || $user->role->name == 'subjefe')
        {
           $employees = $user->workersGte()->where('status',1)->select('id','name_employee','surname_employee')->orderBy('name_employee','ASC')->get();
        }else{
            $employees = $user->appcompany->company->employees()->where('status',1)->select('id','name_employee','surname_employee')->orderBy('name_employee','ASC')->get();
        }
        return response()->json($employees,200);
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
