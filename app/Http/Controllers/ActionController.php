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
use App\Notifications\NewPersonalAction;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasPermission('browse_actions')){
            $user = User::find(Auth::id());
            if($user->firm){
                return view('personalactions.history', compact('user'));
            }else{
                return redirect()->route('employees.editfirm', $user->id);
            }
        }else{
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->hasPermission('browse_actions')){
            $typeactions = ActionType::all();
            $user = User::find(Auth::id());
            $salary = $user->employee->salary;
            $firm = $user->firm;
            if(/* !$salary ||  */!$firm){
                return redirect()->route('employees.edit', $user->id)->with('message', 'Para crear una accón de personal debes tener una firma');
            }else{
                return view('personalactions.new-personal-action', compact('user','typeactions'));
            }
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
        if(Auth::user()->hasPermission('browse_actions')){
            $user = User::find(Auth::id());
            $action = Action::create([
                'other_action'  => $request->otherAction ?? null,
                'description'   => $request->description,
                'created_by'   => $user->employee->id,
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
        
            $boss = $user->employee->jefe;
            $boss->notify(new NewPersonalAction($user->employee));

            return response()->json('Tu acción de personal se creó con éxito',200);
        }else{
            abort(403);
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
        if(Auth::user()->hasPermission('browse_actions')){
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
        }else{

        }
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
        if(Auth::user()->hasPermission('browse_actions_approved')){
            $action = Action::find($id);
            $user = User::find(Auth::id());
            $employee = $action->employee->user;
            $action->update([
                'check_gte' => 3
            ]);

            $employee->notify(new NoApprovedAction);
            

            return 'Has descartado la accion de personal';
        }else{
            abort(403);
        }
    }

    public function approved($id)
    {
        if(Auth::user()->hasPermission('browse_actions_approved')){
            $action = Action::find($id);
            $user = User::find(Auth::id());
            $employee = $action->employee->user;
            if($user->role->name == 'gerente')
            {
                $action->update([
                    'check_gte' => 1
                ]);

                $employee->notify(new ApprovedAction($user->name));

                $rh = $user->companiesResources()->first();
                $rh = User::select('users.*')->where('role_id',3)->join('company_resources','company_resources.user_id','users.id')->where('company_resources.company_id',$rh->company_id)->first();

                $rh->notify(new NewPersonalAction($action->employee));

            }else if($user->role->name == 'rrhh')
            {
                $action->update([
                    'check_rh' => 1
                ]);

                $employee->notify(new ApprovedAction($user->name));
            }

            return 'La acción de personal ha sido aprobada ';
        }else{
            abort(403);
        }
    }
}
