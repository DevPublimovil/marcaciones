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

        if($user->role->name == 'gerente' || $user->role->name == 'subjefe')
        {
            $timestable = $user->workersGte()->active()->first();
            $model =  $user->workersGte()->active()->SearchPaginateAndOrder($timestable->timetable_id);
            return EmployeeResource::collection($model);
        }else{
            $timestable = $user->appcompany->company->employees()->active()->first();
            $model =  $user->appcompany->company->employees()->active()->SearchPaginateAndOrder($timestable->timetable_id);
            return EmployeeResource::collection($model);
        }

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

}
