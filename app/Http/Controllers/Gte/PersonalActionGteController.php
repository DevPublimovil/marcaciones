<?php

namespace App\Http\Controllers\Gte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Employee;
use App\ActionType;
use App\Action;
use App\PersonalAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ApprovedAction;
use App\Notifications\NoApprovedAction;
use PDF;
use App\Notifications\NewPersonalAction;
use Image;


class PersonalActionGteController extends Controller
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

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        if($user->hasPermission('browse_actions'))
        {
            if(!$user->firm)
            {
                return redirect()->route('employees.editfirm', $user->id);
            }

            return view('boss.actions-index', compact('user'));
        }
        else
        {
            abort(403);
        }
    }
}
