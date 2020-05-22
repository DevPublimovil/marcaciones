<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Auth;
use App\Employee;
use App\User;
use App\Marking;
use App\CompanyResource;
use App\Company;
use App\Action;
use App\ActionType;
use App\PersonalAction;
use App\Helper\Assistence;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $resource = $user->companiesResources->first();

        $employees = Employee::where('company_id',$resource->company_id)->orderBy('name_employee','ASC')->paginate(1);
        return view('reports.details', compact('employees'));
    }

    public function create(Request $request, $id)
    {
        $employee = Employee::find($id);
        $markings = Assistence::showAssists($request, $id);

        $data = [
            'employee' => $employee,
            'markings' => $markings
        ];

        $pdf = PDF::loadView('reports.report-one-employee', $data)->setPaper('letter','landscape');

        return $pdf->stream($employee->name_employee . '.pdf');
    }
}
