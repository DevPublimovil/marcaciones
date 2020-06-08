<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Employee;
use App\CompanyResource;
use App\Departament;
use App\Company;
use App\User;
use App\Terminal;
use Image;
use Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreEmployee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = Employee::$columns;
        return view('employees.index', compact('columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terminals = Terminal::orderBy('description','ASC')->get();
        $companies = Company::orderBy('name','ASC')->get();
        $departaments = Departament::orderBy('name','ASC')->get();
        $managers = User::where('role_id',2)->orderBy('name','ASC')->get();
        $user = User::find(Auth::id());
        return view('employees.add-edit-employee', compact('user','companies','departaments','managers','terminals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployee $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
            'nameemployee' => 'required',
            'surnameemployee' => 'required',
            'codemployee' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
                    }else{
                        $employee = Employee::where('cod_marking',$request->codemployee)->where('company_id',$request->company)->where('cod_terminal',$request->terminal)->first();
                        if($employee){
                            if($employee->status){
                                return back()->with('message','Al parecer ya existe un empleado activo con el codigo ' . $request->codemployee)->withInput();
                            }
                        }
                        else{
                            $type = $request->typeemployee;
                            if($type){
                                $user = User::create([
                                    'name' => $request->nameemployee . ' ' . $request->surnameemployee,
                                    'email' => $request->email,
                                    'password' => Hash::make('publimovil'),
                                    'role_id' => 1
                                ]);
                            }
                            $employee = Employee::create([
                                'name_employee' => $request->nameemployee,
                                'surname_employee' => $request->surnameemployee,
                                'cod_marking' => $request->codemployee,
                                'cod_terminal' => $request->terminal,
                                'salary' => $request->salaryemployee,
                                'position' => $request->positionemployee,
                                'company_id' => $request->company,
                                'user_id' => ($type) ? $user->id : null,
                                'departament_id' => $request->departament,
                                'type_employee' => $request->typeemployee,
                                'timetable_id' => $request->timestable,
                                'jefe_id' => $request->boss
                            ]);
                        }
                    }

            return redirect()->route('employees.index')->with('message','¡El empleado ha sido agregado correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terminals = Terminal::orderBy('description','ASC')->get();
        $employee = Employee::find($id);
        $companies = Company::orderBy('name','ASC')->get();
        $departaments = Departament::orderBy('name','ASC')->get();
        $managers = User::where('role_id',2)->orderBy('name','ASC')->get();
        $user = User::find(Auth::id());
        return view('employees.add-edit-employee', compact('user','employee','companies','departaments','managers','terminals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('employees.edit-profile', compact('user'));
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
        $emp = Employee::find($id);
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$emp->user->id,
            'nameemployee' => 'required',
            'surnameemployee' => 'required',
            'codemployee' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
                    }else{
                        $employee = Employee::where('cod_marking',$request->codemployee)->where('company_id',$request->company)->where('cod_terminal',$request->terminal)->first();
                        if($employee){
                            if($employee->id == $emp->id)
                            {
                                $type = $request->typeemployee;
                                if($type){
                                    $emp->user->update([
                                        'name' => $request->nameemployee . ' ' . $request->surnameemployee,
                                        'email' => $request->email,
                                    ]);
                                }
                            }else{
                                if($employee->status){
                                    return back()->with('message','Al parecer ya existe un empleado activo con el codigo ' . $request->codemployee)->withInput();
                                }
                            }
                        }
                    }
                    
                $emp->update([
                    'name_employee' => $request->nameemployee,
                    'surname_employee' => $request->surnameemployee,
                    'cod_marking' => $request->codemployee,
                    'cod_terminal' => $request->terminal,
                    'salary' => $request->salaryemployee,
                    'position' => $request->positionemployee,
                    'company_id' => $request->company,
                    'departament_id' => $request->departament,
                    'type_employee' => $request->typeemployee,
                    'timetable_id' => $request->timestable,
                    'jefe_id' => $request->boss
                ]);

            return redirect()->route('employees.index')->with('message','¡El empleado ha sido actualizado correctamente!');
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

     public function markings(Request $request)
     {
        return view('markings.details');
     }

     public function editFirm($id)
     {
        $user = User::find($id);
        return view('employees.edit-firm', compact('user'));
     }

     public function updateFirm(Request $request, $id)
     {
        //verifico si obtengo datos de la imagen
        if($request->imagefirma)
        {
            //elimino los caracteres no necesarios de la cadena que obtengo
            $cadena = str_replace('data:image/png;base64,', '', $request->imagefirma);
            $cadena = str_replace(' ', '+', $cadena);
            //Utilizo intervention image para codificar la cadena a imagen
            $img = Image::make($cadena)->encode('png', 75);
            //Establezco un nombre unico para la imagen
            $nombreImagen = $id.'firma.png';
            //Guardo la imagen en la carpeta storage
            Storage::disk('public')->put('firms/' . $nombreImagen, $img);
            //Busco el empleado
            $user = User::find($id);
            //Actualizo la ruta de la firma para el empleado
            $user->update([
                'firm' => 'firms/' . $nombreImagen
            ]);
        }
        //Retorno un mensaje con la respuesta
        if($user->role->name != 'empleado'){
            return redirect()->route('actions.index');
        }else{
            return redirect()->route('employees.edit', $id)->with('mensaje','¡Su firma se actualizó correctamente!');
        }
        
     }
}
