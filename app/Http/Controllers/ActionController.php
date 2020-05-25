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

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('personalactions.history');
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
        return view('personalactions.new-personal-action', compact('user','typeactions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $action = Action::create([
            'other_action'  => $request->other_action ?? null,
            'description'   => $request->description,
            'employee_id'   => $request->employee,
        ]);

        if($request->actions)
        {
            foreach ($action as $key => $value) {
                PersonalAction::create([
                    'action_id'         => $action->id,
                    'type_action_id'    => $value
                ]);
            }
        }

        return redirect()->route('home')->with('message', 'Tu acción de personal se ha creado con exitó, puedes revisar su estado en tu historial');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        return back()->with('message', '¡' . $employee->name . 'ha sido notificado!');
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

        $employee->notify(new NoApprovedAction);

        return back()->with('message', '¡' . $employee->name . 'ha sido notificado!');
    }
}
