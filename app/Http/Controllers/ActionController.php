<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\ActionType;
use App\Action;
use App\PersonalAction;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ApprovedAction;
use App\Notifications\NoApprovedAction;
use PDF;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        return view('personalactions.history', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typeactions = ActionType::all();
        $user = User::find(Auth::id());
        $salary = $user->employee->salary;
        $firm = $user->firm;
        if(!$salary || !$firm){
            return redirect()->route('employees.edit', $user->id)->with('message', 'Para crear una accón de personal debes proporcionar tu salario y tener una firma');
        }else{
            return view('personalactions.new-personal-action', compact('user','typeactions'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::id());
        $action = Action::create([
            'other_action'  => $request->otherAction ?? null,
            'description'   => $request->description,
            'employee_id'   => $user->employee->id,
        ]);

        if($request->actions)
        {
            foreach ($request->actions as $key => $value) {
                PersonalAction::create([
                    'action_id'         => $action->id,
                    'type_action_id'    => $value
                ]);
            }
        }

        if($action){
            return 'exito';
        }else{
            return 'exito';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $action = Action::find($id);
        $employee = $action->employee;
        $company = $employee->company;
        $resource = $company->resourceCompany()->first();
        $rh =  $resource->user;
        $personal_actions = $action->personalaction;
        $pdf = PDF::loadView('reports.actionpdf',[
            'action' => $action,
            'rh' => $rh,
            'personal_actions' => $personal_actions,
        ])->setPaper('letter','portrait');
        return $pdf->stream($employee->name_employee . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function noApproved($id)
    {
        
        $action = Action::find($id);
        $user = User::find(Auth::id());
        $employee = $action->employee->user;

        if($user->role->name == 'gerente')
        {
            $action->update([
                'check_gte' => 3
            ]);
        }else if($user->role->name == 'rrhh')
        {
            $action->update([
                'check_rh' => 3
            ]);
        }

        $employee->notify(new NoApprovedAction);

        return 'todo bien';
    }

    public function approved($id)
    {
        
        $action = Action::find($id);
        $user = User::find(Auth::id());
        $employee = $action->employee->user;
        if($user->role->name == 'gerente')
        {
            $action->update([
                'check_gte' => 1
            ]);
        }else if($user->role->name == 'rrhh')
        {
            $action->update([
                'check_rh' => 1
            ]);
        }

        $employee->notify(new ApprovedAction);

        return 'todo bien';
    }
}
