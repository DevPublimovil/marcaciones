<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Timetable;
use Auth;
use App\CompanyResource;
use App\User;
use App\Http\Resources\TimeTableResource as Time;

class TimestableJsonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::find(Auth::id());
        if($user->role->name == 'gerente')
        {
            $company = $user->companiesResources->first();
            $company = $company->company;
            $resource = User::select('users.*')->where('role_id',3)->join('company_resources','company_resources.user_id','users.id')->first();
        }else{
            $resource = $user;
        }

        $timestable = $resource->timetables()->orderBy('created_at','DESC')->get();
        
        return response()->json(Time::collection($timestable), 200);
    }
}
