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
use \Carbon\Carbon as Fecha;
use Illuminate\Support\Collection;
use App\Webster_checkinout;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        
        if(Auth::user()->hasPermission('browse_reports')){
            $user = User::find(Auth::id());
            if($user->role->name == 'rrhh')
            {
                $resource = $user->appcompany->first();

                $employees = Employee::where('company_id',$resource->company_id)->where('status',1)->SearchEmployee($request->employee)->orderBy('name_employee','ASC')->paginate(2);
            }
            else if($user->role->name == 'gerente' || $user->role->name == 'subjefe')
            {
                $employees = $user->workersGte()->where('status',1)->SearchEmployee($request->employee)->orderBy('name_employee','ASC')->paginate(2);
            }
        
            return view('reports.details', compact('employees'));   
        }else{
            abort(403);
        }
    }

    public function create(Request $request, $id)
    {
        $employee = Employee::find($id);
        $markings = Assistence::showAssists($request, $id);

        $data = [
            'employee' => $employee,
            'markings' => $markings,
            'start' => $request->start_date,
            'end' => $request->end_date
        ];

        $pdf = PDF::loadView('reports.report-one-employee', $data)->setPaper('letter','landscape');

        return $pdf->stream($employee->name_employee . '.pdf');
    }

    public function createAll(Request $request)
    {
        if(Auth::user()->hasPermission('create_reports')){
            ini_set("max_execution_time", 3600);
            $name = Fecha::now()->toDateString();
            $user = User::find(Auth::id());
            if($request->employees){
                $employees = explode(',',$request->employees);
            }else{
                if($user->role->name == 'gerente' || $user->role->name == 'subjefe'){
                    $employees = $user->workersGte()->where('status',1)->get();
                }else{
                    $employees = $user->appCompany->company->employees()->where('status',1)->get();
                }
            }
            
            foreach ($employees as $key => $employee) {
                $markings = Assistence::showAssists($request, $employee->id ?? $employee);

                $data[] = [
                    'employee' => Employee::find($employee->id ?? $employee),
                    'markings' => $markings,
                ];
            }

            $pdf = PDF::loadView('reports.report-all-employees', [
                'data' => $data,
                'start' => $request->start_date,
                'end' => $request->end_date
            ])->setPaper('letter','landscape');

            return $pdf->stream($name . '.pdf');
        }else{
            abort(403);
        }
    }
}
