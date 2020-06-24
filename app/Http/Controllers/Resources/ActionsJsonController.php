<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Employee;
use App\Action;
use App\PersonalAction;
use App\Http\Resources\ActionResource;
use App\CompanyResource;
use App\AppSession;
use Illuminate\Support\Collection;

class ActionsJsonController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showActions($type)
    {
        $user = User::find(Auth::id());
        if($type == 1)
        {
            return $this->showPendingActions($user);
        }else if($type == 2){
            return $this->showApprovedActions($user);
        }
    }


    public function showPendingActions($user)
    {
        if($user->role->name == "rrhh"){
            $query = $user->appcompany->company->employees()
                    ->select('actions.*','users.name')
                    ->join('users','users.id','employees.user_id')
                    ->join('actions','actions.created_by','users.id')
                    ->where('actions.check_gte','=',1)
                    ->where('check_rh','=',0)
                    ->orderBy('created_at','DESC')
                    ->get();
            $queryTwo = $user->appcompany->company->employees()
                        ->select('actions.*','employees.name_employee','employees.surname_employee')
                        ->join('actions','actions.employee_id','employees.id')
                        ->where('actions.check_gte','=',1)
                        ->where('actions.check_employee','=',1)
                        ->where('check_rh','=',0)
                        ->orderBy('created_at','DESC')
                        ->get();

            $query = $query->merge($queryTwo);
            
            $query = $query->sortByDesc('created_at');
        }
        else if($user->role->name == "gerente" || $user->role->name == "subjefe")
        {
            $query = $user->workersGte()->select('actions.*','users.name')
                    ->join('users','users.id','employees.user_id')
                    ->join('actions','actions.created_by','users.id')
                    ->orderBy('created_at','DESC')
                    ->where('check_gte','=',0)
                    ->orderBy('created_at','DESC')
                    ->get();
        }

        $data = ActionResource::collection($query);
        return response()->json([
            'actions' => $data,
            'user' => $user
        ], 200);
    }

    public function showApprovedActions($user)
    {
        if($user->role->name == "rrhh"){
            $query = $user->appcompany->company->employees()
                    ->select('actions.*','users.name')
                    ->join('users','users.id','employees.user_id')
                    ->join('actions','actions.created_by','users.id')
                    ->where('actions.check_gte','=',1)
                    ->where('check_rh','=',1)
                    ->orderBy('created_at','DESC')
                    ->get();
            $queryTwo = $user->appcompany->company->employees()
                    ->select('actions.*','employees.name_employee','employees.surname_employee')
                    ->join('actions','actions.employee_id','employees.id')
                    ->where('actions.check_gte','=',1)
                    ->where('actions.check_employee','=',1)
                    ->where('check_rh','=',1)
                    ->orderBy('created_at','DESC')
                    ->get();

            $query = $query->merge($queryTwo);

            $query = $query->sortByDesc('created_at');
        }
        else if($user->role->name == "gerente" || $user->role->name == "subjefe")
        {
            $query = $user->workersGte()->select('actions.*','users.name')
                        ->join('users','users.id','employees.user_id')
                        ->join('actions','actions.created_by','users.id')
                        ->orderBy('created_at','DESC')
                        ->where('check_gte','=',1)
                        ->orderBy('created_at','DESC')
                        ->get();
            $queryTwo = $user->actionsEmp;

            $query = $query->merge($queryTwo);

            $query = $query->sortByDesc('created_at');
        }

        $data = ActionResource::collection($query);
        return response()->json([
            'actions' => $data,
            'user' => $user
             ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        $query = $user->actionsEmp()
                ->orWhere('actions.employee_id', $user->employee->id)
                ->orderBy('created_at','DESC')
                ->get();
        $data = ActionResource::collection($query);

        return response()->json($data, 200);
    }
}
