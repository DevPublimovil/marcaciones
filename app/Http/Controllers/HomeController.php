<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Employee;
use App\User;
use Carbon\Carbon as Fecha;
use App\AppSession;
use App\Marking;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(Auth::id());
        if($user->role->name == 'gerente')
        {
            return redirect()->route('actions.index');
        }else if( $user->role->name == 'rrhh'){
            $companies = $user->companiesResources()->with(['company'])->get();
            return view('select-company', compact('companies','user'));
        }else if($user->role->name == 'admin'){
            return redirect()->route('voyager.dashboard');
        }else{
            $employee = $user->employee->id;
            return view('home', compact('employee'));
        }
    }

    public function selectCompany(Request $request)
    {
        $company = AppSession::updateOrCreate([
            'user_id' => Auth::id()
        ],[ 'company_id' => $request->company]);

        return redirect()->route('actions.index');
    }

    public function homeclockbot()
    {
        $user = User::find(Auth::id());
        $employee = $user->employee->id;
        return view('subjefe.home', compact('employee'));
    }
}
