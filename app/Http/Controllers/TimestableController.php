<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timetable;
use App\User;
use Auth;
use App\Employee;
use App\DaysTable;

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
            $rh = User::find(Auth::id());
            if(Auth::user()->role_id != 3)
            {
                $rh = $rh->companiesResources()->first();
                $rh = User::select('users.*')->where('role_id',3)->join('company_resources','company_resources.user_id','users.id')->where('company_resources.company_id',$rh->company_id)->first();
            }
            $timestable = Timetable::create([
                'hour_in' => $request->in,
                'hour_out' => $request->out,
                'created_by' => $rh->id
            ]);

            foreach (json_decode($request->days) as $key => $day) {
                $time = DaysTable::firstOrNew([
                    'timetable_id' =>$timestable->id,
                    'day' => $day
                ]);

                if (!$time->exists){
                    $time->save();
                }
            }

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
        //if(Auth::user()->hasPermission('update_timestables')){
            $timestable = Timetable::find($id);
            $timestable->update([
                'hour_in' => $request->in,
                'hour_out' => $request->out,
            ]);

            if($request->days){
                foreach (json_decode($request->days) as $key => $day) {
                    $timestable->days()->where('day', '!=', $day)->delete();
                }
                foreach (json_decode($request->days) as $key => $day) {
                    $time = DaysTable::firstOrNew([
                        'timetable_id' =>$timestable->id,
                        'day' => $day
                    ]);

                    if (!$time->exists){
                        $time->save();
                    }
                }
            }

            return response()->json('!El horario ha sido actualizado con éxito!', 200);
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timestable = Timetable::find($id);
        $timestable->delete();

        return 'El horario se ha eliminado';
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

            return response()->json('!Se ha actualizado el horario para tus empleados', 200);
        }
    }
}
