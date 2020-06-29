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
use App\Helper\ResizeImage;
use App\Notifications\NewPersonalAction;
use Image;

class ActionController extends Controller
{

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
        if(!$user->hasPermission('browse_actions'))
        {
            return back()->with([
                'message' => 'No tienes permisos para el recurso',
                'type' => 'warning'
            ]);
        }else if(!$user->firm){
            return view('edit-firm', compact('user'));
        }

        switch ($user->role->name) {
            case 'empleado':
                    return view('personalactions.history', compact('user'));
                break;
            case 'gerente':
                    return view('boss.actions-index');
            case 'subjefe':
                return view('boss.actions-index');
            case 'rrhh':
                    return view('rh.actions-index');
            default:
                return back()->with([
                    'message' => 'No tienes permisos para el recurso',
                    'type' => 'warning'
                ]);
            break;
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::id());
        if(!$user->hasPermission('browse_actions')){
            return back()->with([
                'message'   => 'No tienes permiso para crear una acción de personal',
                'type'      => 'warning'
            ]);
        }

        $typeactions = ActionType::all();
        $user = User::find(Auth::id());
        if(!$user->firm){
            return redirect()->route('employees.edit', $user->id)->with('message', 'Para crear una accón de personal debes tener una firma');
        }

        if($user->role->name == 'empleado' || $user->role->name == 'rrhh'){
            return view('personalactions.new-personal-action', compact('user','typeactions'));
        }else{
            $employees = $user->workersGte()->orderBy('name_employee','ASC')->get();
            return view('boss.new-personal-action', compact('user','typeactions','employees'));
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
        $user = User::find(Auth::id());

        if($request->file('attached'))
        {
            $path = $this->resize($request->file('attached'));
        }

        $action = Action::create([
            'other_action'  => $request->otherAction ?? null,
            'description'   => $request->description,
            'attached'      => $path ?? NULL,
            'check_gte'     => ($user->role->id != 1 && $request->employee) ? 1 : null,
            'employee_id'   => ($user->role->id != 1 && $request->employee) ? $request->employee : NULL,
            'created_by'    => $user->id,
        ]);

        if($request->actions)
        {
            PersonalAction::create([
                'action_id'         => $action->id,
                'type_action_id'    => $request->actions
            ]);
        }

        if($user->role->id != 1 && $request->employee)
        {
            $employee = Employee::find($request->employee);
            if($employee->type_employee == 1)
            {
                $employee->user->notify(new NewPersonalAction($user));
            }
        }else{
            $boss = $user->employee->jefe;
            $boss->notify(new NewPersonalAction($user));
        }

        return response()
                    ->json([ 
                        'message'   => 'La acción de personal se ha generado con éxito',
                        'type'      => 'success',
                        'action'    => $action->id,
                    ], 200);
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
            $action = Action::findOrFail($id);
            $employee = ($action->employee_id) ? $action->employee : $action->user->employee;
            if($user->role->name == 'empleado')
            {
                if($user->employee->id != $employee->id){
                    return back();
                }
            }

            if($user->role->name == 'empleado'){
                if($employee->id != $user->employee->id){
                    return back()->with([
                        'message' => 'Estas intentando acceder a una acción de personal que no te pertenece',
                        'type' => 'danger'
                    ]);
                }
            }
            $company = $employee->company;
            $resource = $company->resourceCompany()->join('users','users.id','company_resources.user_id')->where('users.role_id',3)->first();
            $rh =  $resource->user;
            $pdf = PDF::loadView('reports.actionpdf',[
                'action' => $action,
                'rh' => $rh,
                'employee' => $employee,
            ])->setPaper('letter','portrait');
            return $pdf->stream($employee->name_employee . '.pdf');
        }else{
            abort(403);
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
            $personalaction = PersonalAction::updateOrCreate([
                'action_id'   => $action->id
            ],['type_action_id' => $request->actions]);
            $action->update(['other_action' => null]);
        }else{
            $action->personalaction->delete();
        }

        return response()
            ->json([ 
                'message'   => 'La acción de personal se ha actualizado con éxito',
                'type'      => 'success',
                'action'    => $action->id,
            ], 200);
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
        $user = User::find(Auth::id());
        if(!$user->hasPermission('edit_actions')){
            return response()->json([
                'message'   => 'No tienes permisos para aprobar la acción de personal',
                'type'      => 'warning'
            ]);
        }

        $action = Action::find($id);
        if($user->role->name == 'empleado')
        {
            $action->update(['check_employee' => 1]);
            $rh = $user->employee->company->resourceCompany()->with('user')->get();
            $rh = $rh->where('user.role_id',3)->first();
            $rh->user->notify(new NewPersonalAction($user));
        }

        return response()->json([
            'message'   => '¡Tus cambios se han guardado corractamente!',
            'type'      => 'success'
        ]);
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
