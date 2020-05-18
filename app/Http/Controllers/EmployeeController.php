<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Employee;
use App\CompanyResource;
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

    public function getEmployees()
    {
        $user = User::find(Auth::id());
        $resource = $user->companiesResources()->orderBy('id','ASC')->first();

        $query = Employee::where('company_id',$resource->company_id)->with(['user','departament:id,display_name','company:id,display_name'])->orderBy('surname_employee','ASC')->get();

        return DataTables::of($query)
        ->addColumn('avatar', function($row){
            $image =  $row->user->avatar;
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
