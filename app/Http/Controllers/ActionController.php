<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\ActionType;
use App\Action;
use App\PersonalAction;

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
        $personal_actions = $user->employee->actions()->orderBy('created_at','DESC')->paginate(6);
        return view('personalactions.history', compact('user','personal_actions'));
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
        //
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
}
