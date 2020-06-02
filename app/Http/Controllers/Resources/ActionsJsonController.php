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
            $company = CompanyResource::where('user_id',$user->id)->first();
            $query = Action::whereHas('employee', function ($query) use ($company) {
                $query->where('company_id',$company->company_id);
            })->CheckGte()
                ->NoCheckRh()
                ->with(['employee'])
                ->orderBy('actions.created_at','DESC')->get();
        }
        else if($user->role->name == "gerente")
        {
            $query = Action::whereHas('employee', function ($query) use ($user) {
                $query->where('jefe_id',$user->id);
            })->NoCheckGte()
                ->with(['employee'])
                ->orderBy('actions.created_at','DESC')->get();
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
            $company = CompanyResource::where('user_id',$user->id)->first();
            $query = Action::whereHas('employee', function ($query) use ($company) {
                $query->where('company_id',$company->company_id);
            })->CheckRh()
                ->with(['employee'])
                ->orderBy('actions.created_at','DESC')->get();
        }
        else if($user->role->name == "gerente")
        {
            $query = Action::whereHas('employee', function ($query) use ($user) {
                $query->where('jefe_id',$user->id);
            })->CheckGte()
                ->with(['employee'])
                ->orderBy('actions.created_at','DESC')->get();
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
        $employee = $user->employee;
        $query = $employee->actions()->orderBy('created_at','DESC')->get();
        $data = ActionResource::collection($query);

        return response()->json($data, 200);
    }
}
