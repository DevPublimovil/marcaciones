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
        }else if($type == 3)
        {
            return $this->showNotApproved($user);
        }
    }

    public function showActionsPendings()
    {
        $user = User::find(Auth::id());
        $company = $user->appcompany->company;
        
        $query = $company->actions()->checkGte()->NoCheckRh()->get();
        
        $data = ActionResource::collection($query);
        return response()->json([
            'actions' => $data,
            'user' => $user
        ], 200);
    }

    public function showActionsApproved()
    {
        $user = User::find(Auth::id());
        $company = $user->appcompany->company;
        
        $query = $company->actions()->checkGte()->CheckRh()->get();

        $data = ActionResource::collection($query);
        return response()->json([
            'actions' => $data,
            'user' => $user
             ], 200);
    }

    public function showActionsNotApproved()
    {
        $user = User::find(Auth::id());
        $company = $user->appcompany->company;
        
        $query = $company->actions()->where('check_rh',0)->get();

        $data = ActionResource::collection($query);
        return response()->json([
            'actions' => $data,
            'user' => $user
            ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        if($user->role->id == 1)
        {
            $query = $user->actionsEmp()
                ->orWhere('actions.employee_id', $user->employee->id)
                ->orderBy('created_at','DESC')
                ->get();
        }else{
            $query = $user->actionsEmp()
                ->whereNull('actions.employee_id')
                ->orderBy('created_at','DESC')
                ->get();
        }
        $data = ActionResource::collection($query);

        return response()->json($data, 200);
    }
}
