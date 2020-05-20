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
        $resource = $user->companiesResources->first();
        //$query = Employee::where('company_id',$resource->company_id);

        $column = $this->selectColumn($request->column);
        $model = Employee::SearchPaginateAndOrder($column, $resource->company_id);

        $query = EmployeeResource::collection($model);
        
        return $query;
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
