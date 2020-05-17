<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Employee;
use App\Terminal;
use App\TerminalRrhh;
use App\TerminalEmployee;
use App\User;
use DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employees.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * retorna todos los empleados administrativos
     * 
     * @return \Illuminate\Http\Response
     */

    public function getempAdmin()
    {
        $user = User::find(Auth::id());
        $terminal = $user->terminalrrhh;
        return $terminal;

        return DataTables::of(User::all())
        ->addColumn('avatar', function($row){
            $image =  $row->avatar;
            return view('partials.avatar', compact('image'));
        })
        ->addColumn('actions', function($row){
            $profile = route('employees.show', $row->id);
            return view('partials.actions', compact('profile'));
        })
        ->make(true);
    }
    /**
     * retorna todos los empleados operativos
     * 
     * @return \Illuminate\Http\Response
     */

    public function getempOpe()
    {
        return DataTables::of(User::all())
        ->addColumn('avatar', function($row){
            $image =  $row->avatar;
            return view('partials.avatar', compact('image'));
        })
        ->addColumn('actions', function($row){
            $profile = route('employees.show', $row->id);
            return view('partials.actions', compact('profile'));
        })
        ->make(true);
    }
}
