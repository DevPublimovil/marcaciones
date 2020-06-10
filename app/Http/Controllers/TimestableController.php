<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timetable;
use App\User;
use Auth;
use App\Employee;

class TimestableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasPermission('browse_timestables')){
            return view('timestables.index');
        }
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
        if(Auth::user()->hasPermission('store_timestables')){
            $rh = Auth::id();
            $timestable = Timetable::create([
                'hour_in' => $request->in,
                'hour_out' => $request->out,
                'created_by' => $rh
            ]);

            return response()->json('!El horario ha sido creado con exito!', 200);
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
        if(Auth::user()->hasPermission('update_timestables')){
            $timestable = Timetable::find($id);
            $timestable->update([
                'hour_in' => $request->in,
                'hour_out' => $request->out,
            ]);

            return response()->json('!El horario ha sido actualizado con éxito!', 200);
        }
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

    public function changeTimesEmployee(Request $request)
    {
        if(Auth::user()->hasPermission('store_timestables')){
            $timestable = Timetable::find($request->timestable);
            foreach ($request->employees as $key => $value) {
                $employee = Employee::find($value);
                $employee->update([
                    'timetable_id' => $timestable->id
                ]);
            }

            return response()->json('!Se ha actualizado el horario para tus empleados con éxito!', 200);
        }
    }
}
