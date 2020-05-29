<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function uploadData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new EmployeeImport, $file);
        return 'ok';
    }

    public function index()
    {
        return view('admin.upload-data');
    }
}
