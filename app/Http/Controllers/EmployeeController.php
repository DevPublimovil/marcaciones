<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Employee;
use App\CompanyResource;
use App\User;
use Image;
use Storage;
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
