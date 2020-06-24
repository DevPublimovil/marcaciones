<?php

namespace App\Http\Controllers;

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
            //$salary = $user->employee->salary;
            $firm = $user->firm;
            if(/* !$salary ||  */!$firm){
                return redirect()->route('employees.edit', $user->id)->with('message', 'Para crear una accón de personal debes tener una firma');
            }else{
                return view('personalactions.new-personal-action', compact('user','typeactions'));
            }
        }
    }

    public function createForEmployee($id)
    {
        $employee = $id;
        $typeactions = ActionType::all();
        $user = User::find(Auth::id());
        return view('personalactions.new-personal-action', compact('user','typeactions','employee'));
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

            if($request->file('attached'))
            {

                $path = Storage::disk('public')->putFile('adjuntos', $request->file('attached'));
                $image = Image::make(Storage::disk('public')->get($path));

                $image->resize(1280, null, function($constrait){
                    $constrait->aspectRatio();
                    $constrait->upsize();
                });

                Storage::disk('public')->put($path, (string) $image->encode('jpg', 50));
            }

            $user = User::find(Auth::id());
            $action = Action::create([
                'other_action'  => $request->otherAction ?? null,
                'description'   => $request->description,
                'attached'      => $path ?? null,
                'check_gte'     => ($user->role->id == 2 && $request->employee) ? 1 : 0,
                'employee_id'   => ($user->role->id == 2 && $request->employee) ? $request->employee : null,
                'created_by'    => $user->id,
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

            if($user->role->id == 2 && $request->employee)
            {
                $employee = Employee::find($request->employee);
                if($employee->type_employee == 1)
                {
                    $employee->user->notify(new NewPersonalAction($user));
                    return response()->json('Tu acción de personal se creó con éxito y tu empleado ha sido notificado',200);
                }

                return response()->json('Tu acción de personal se creó con éxito',200);
            }else{
                $boss = $user->employee->jefe;
                $boss->notify(new NewPersonalAction($user));
                return response()->json('Tu acción de personal se creó con éxito',200);
            }
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
        $user = User::find(Auth::id());
        if(Auth::user()->hasPermission('browse_actions')){
            $action = Action::find($id);
            $employee = ($action->employee_id) ? $action->employee : $action->user->employee;
            if($user->role->name == 'empleado'){
                if($employee->id != $user->employee->id){
                    return back()->with([
                        'message' => 'Estas intentando acceder a una acción de personal que no te pertenece',
                        'type' => 'danger'
                    ]);
                }
            }
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
        $action = Action::find($id);
        if(!$action->check_gte){
            $action = $action->load('personalaction');
            $typeactions = ActionType::all();
            $user = User::find(Auth::id());
            return view('personalactions.new-personal-action', compact('user','typeactions','action'));
        }else{
            return back()->with([
                'message' => 'Estas intentando editar una acción de personal que ya fue aprobada',
                'type' => 'danger'
            ]);
        }
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
        $user = User::find(Auth::id());
        $action = Action::find($id);
        $action->update([
            'other_action'  => $request->otherAction ?? null,
            'description'   => $request->description,
            'created_by'   => $user->id,
        ]);

        if($request->actions)
        {
            foreach ($request->actions as $key => $value) {
                $action->personalaction()->where('type_action_id', '!=', $value)->delete();
            }

            foreach ($request->actions as $key => $item) {
                $pantalla = PersonalAction::firstOrCreate([
                    'action_id'   => $action->id,
                    'type_action_id'      => $item
                ]);
            }

        }

       return response()->json('Tu acción de personal se actualizó con éxito',200);
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
            $employee = $action->user;
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
            $employee = ($action->employee_id) ? $action->employee->user : $action->user;
            if($user->role->name == 'gerente' || $user->role->name == 'subjefe')
            {
                $action->update([
                    'check_gte' => 1
                ]);

                $employee->notify(new ApprovedAction($user->name));

                $rh = $user->companiesResources()->first();
                $rh = User::select('users.*')->where('role_id',3)->join('company_resources','company_resources.user_id','users.id')->where('company_resources.company_id',$rh->company_id)->first();

                $rh->notify(new NewPersonalAction($action->user));

            }else if($user->role->name == 'rrhh')
            {
                $action->update([
                    'check_rh' => 1
                ]);

                $employee->notify(new ApprovedAction($user->name));
            }else if($user->role->name == 'empleado')
            {
                $action->update([
                    'check_employee' => 1
                ]);
                
                
                $rh = $user->employee->company->resourceCompany()->first();
                $rh = $rh->user;
                $rh->notify(new NewPersonalAction($user));
            }

            return 'La acción de personal ha sido aprobada ';
        }else{
            abort(403);
        }
    }

    public function myactions()
    {
        $user = User::find(Auth::id());
        if($user->firm){
            return view('subjefe.actions', compact('user'));
        }else{
            return redirect()->route('employees.editfirm', $user->id);
        }
    }
}
