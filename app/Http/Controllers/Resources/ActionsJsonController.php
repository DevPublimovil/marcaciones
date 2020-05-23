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

class ActionsJsonController extends Controller
{
    public function showPendingActions()
    {
        $user = User::find(Auth::id());

        $data = ActionResource::collection(Action::all());
        return response()->json($data, 200);
    }

    public function showApprovedActions()
    {
        $user = User::find(Auth::id());

        $data = ActionResource::collection(Employee::all());
        return response()->json($data, 200);
    }
}
