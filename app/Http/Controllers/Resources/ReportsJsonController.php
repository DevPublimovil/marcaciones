<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Employee;
use App\User;
use App\Marking;
use App\CompanyResource;
use App\Company;
use App\Action;
use App\ActionType;
use App\PersonalAction;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\DetailsResource;
use Carbon\Carbon as Fecha;
use App\Helper\Assistence;

class ReportsJsonController extends Controller
{

    public function showAssistsDetails(Request $request, $id)
    {
        $markings = Assistence::showAssists($request, $id);
        return response()->json($markings, 200);
    }

    public function showDetail()
    {

        /* if($request->start_date && $request->end_date)
        {
            $period = Fecha::parse($request->start_date)->toPeriod($request->end_date);
        }else{
            $start_date = Fecha::now()->startOfWeek()->format('Y-m-d');
            $end_date = Fecha::now()->endOfWeek()->format('Y-m-d');
            $period = Fecha::parse($start_date)->toPeriod($end_date);
        }

        foreach ($period as $key => $value) {
            $marking = Marking::where('serialno',$employee)
        } */
    }

    
}
