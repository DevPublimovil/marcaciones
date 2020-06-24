<?php
namespace App\Helper;
use App\Employee;
use \Carbon\Carbon as Fecha;
use App\Marking;
use App\Action;
use Illuminate\Support\Collection;

class Assistence {

    public static function showAssists($request, $id)
    {
        $employee = Employee::find($id);

        $markings = collect();

        $period = collect(Fecha::parse($request->start_date)->toPeriod($request->end_date));

        foreach ($period as $key => $value) {
            $marking = Marking::HaveMarking($employee->cod_marking, $employee->cod_terminal)->whereDate('created_at',$value)->first();

            if($employee->type_employee == 1 && $employee->user)
            {
                $action = $employee->user->actionsEmp()->whereDate('created_at',$value)->first();
            }else{
                $action = Action::where('employee_id',$employee->id)->whereDate('created_at',$value)->first();
            }

            $permission = ($action) ? 'SI' : 'AUSENTE';


            if($marking)
            {
                $markings->push([
                    'date'          => Fecha::parse($value)->format('d/m/Y'),
                    'day'           => Fecha::parse($value)->locale('es')->isoFormat('dddd'),
                    'in'            => Fecha::parse($marking->check_in)->format('H:i a'),
                    'out'           => Fecha::parse($marking->check_out)->format('H:i a'),
                    'hours_worked'  => $marking->hours_worked ?? null,
                    'extra_hours'   => $marking->extra_hours ?? null,
                    'late_arrivals' => $marking->late_arrivals ?? null,
                    'permission'    => ($marking->check_in && $marking->check_out) ? '' : $permission
                ]);
            }
            else
            {
                $markings->push([
                    'date'          => Fecha::parse($value)->format('d/m/Y'),
                    'day'           => Fecha::parse($value)->locale('es')->isoFormat('dddd'),
                    'in'            => 'sin marcación',
                    'out'           => 'sin marcación',
                    'hours_worked'  => '',
                    'extra_hours'   => '',
                    'late_arrivals' => '',
                    'permission'    => $permission
                ]);
            }
        }

        $filtered_worked    = $markings->where('hours_worked','!=',null);
        $filtered_worked    = $filtered_worked->pluck('hours_worked');
        $filtered_extra    = $markings->where('extra_hours','!=',null);
        $filtered_extra    = $filtered_extra->pluck('extra_hours');
        $filtered_minutes    = $markings->where('late_arrivals','!=',null);
        $filtered_minutes    = $filtered_minutes->pluck('late_arrivals');

        return collect([
            'markings' => $markings,
            'total_hours_worked'    => ($markings->isEmpty()) ? null : self::AddPlayTime($filtered_worked),
            'total_extra_hours'     => ($markings->isEmpty()) ? null : self::AddPlayTime($filtered_extra),
            'total_late_arrivals'   => ($markings->isEmpty()) ? null : self::AddPlayTime($filtered_minutes)
            ]);
    }

    public static function AddPlayTime($times) {
        $minutes = 0;

        foreach ($times as $time) {
            list($hour, $minute) = explode('.', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;
        // returns the time already formatted
        return  sprintf('%02d:%02d', $hours, $minutes);
    }

   /*  public static function convertMinutes($times)
    {
        $total = 0;
        foreach ($times as  $time) {
            $total += $time;
        }

        $time = floor($total / 60);
        $total -= $time * 60;

        return  sprintf('%02d:%02d', $time, $total);
    } */

}
