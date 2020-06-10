<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use App\Imports\UserImport;
use App\Imports\TimetableImport;
use Maatwebsite\Excel\Facades\Excel;
use App\User;

class AdminController extends Controller
{
    
    public function index()
    {
        $this->authorize('browse_bread');
        $users = User::where('role_id',3)->get();
        return view('admin.upload-data',compact('users'));
    }

    public function uploadData(Request $request)
    {
        $file = $request->file('file');
            if($file){
                    if($request->model == 'employee'){
                        Excel::import(new EmployeeImport, $file);
                    }else if($request->model == 'manager'){
                        Excel::import(new UserImport, $file);
                    }else if($request->model == 'timestables'){
                        Excel::import(new TimetableImport($request->rrhh), $file);
                    }
                }
            
            return back()->with('message', '¡Los datos han sido guardados con éxito!');
    }

}
