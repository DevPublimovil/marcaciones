<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Employee;
use App\CompanyResource;
use App\Departament;
use App\Company;
use App\User;
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
        $companies = Company::orderBy('name','ASC')->get();
        $departaments = Departament::orderBy('name','ASC')->get();
        $managers = User::where('role_id',2)->orderBy('name','ASC')->get();
        return view('employees.add-edit-employee', compact('companies','departaments','managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployee $request)
    {
        $user = User::create([
            'name' => $request->nameemployee . ' ' . $request->surnameemployee,
            'email' => $request->email,
            'password' => Hash::make('publimovil'),
            'role_id' => 1
        ]);

        $user->employee()->create([
            'name_employee' => $request->nameemployee,
            'surname_employee' => $request->surnameemployee,
            'cod_marking' => $request->codemployee,
            'cod_terminal' => '3213555',
            'salary' => $request->salaryemployee,
            'position' => $request->positionemployee,
            'company_id' => $request->company,
            'departament_id' => $request->departament,
            'type_employee' => $request->typeemployee,
            'jefe_id' => $request->boss
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        $companies = Company::orderBy('name','ASC')->get();
        $departaments = Departament::orderBy('name','ASC')->get();
        $managers = User::where('role_id',2)->orderBy('name','ASC')->get();
        return view('employees.add-edit-employee', compact('employee','companies','departaments','managers'));
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
        $user = User::find($id);
        if($request->password)
        {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }
        else if($request->salary)
        {
            $user->employee->update([
                'salary' => $request->salary
            ]);
        }
        else
        {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);
        }

        return redirect()->route('home')->with('message', '¡Tus datos se han guardado correctamente!');
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
        return redirect()->route('employees.edit', $id)->with('mensaje','¡Su firma se actualizó correctamente!');
     }
}
