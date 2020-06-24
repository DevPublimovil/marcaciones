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
            $query = $user->appcompany->company->actions()->CheckGte()->NoCheckRh()->with('user')->orderBy('created_at','DESC')->get();
        }
        else if($user->role->name == "gerente" || $user->role->name == "subjefe")
        {
            $query = $user->actionsGte()->noCheckGte()->with('user')->orderBy('created_at','DESC')->get();
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
            $query = $user->appcompany->company->actions()->CheckRh()->with('user')->orderBy('created_at','DESC')->get();
        }
        else if($user->role->name == "gerente" || $user->role->name == "subjefe")
        {
            $query = $user->actionsGte()->checkGte()->orWhere('created_by', $user->id)->with('user')->orderBy('created_at','DESC')->get();
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
        $query = $user->actionsEmp()->orWhere('actions.employee_id', $user->employee->id)->orderBy('created_at','DESC')->get();
        $data = ActionResource::collection($query);

        return response()->json($data, 200);
    }
}
