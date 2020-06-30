<?php

namespace App\Helper;
use App\Employee;
use \Carbon\Carbon as Fecha;
use App\Marking;
use App\Action;
use Illuminate\Support\Collection;
use App\DaysTable;

class Assistence {

    public static function showAssists($request, $id)
    {
        
        $employee = Employee::find($id);

        $markings = collect();

        if($request->start_date && $request->end_date){
            $startday = Fecha::parse($request->start_date)->subDay()->format('Y-m-d');
            $endday = Fecha::parse($request->end_date)->subDay()->format('Y-m-d');
        }
        
        $period = collect(Fecha::parse($startday ?? Fecha::now()->startOfWeek()->format('Y-m-d'))->toPeriod($endday ?? Fecha::now()->endOfWeek()->format('Y-m-d')));
        
        $newPeriod = collect();
        
        $days = DaysTable::where('timetable_id', $employee->timetable_id)->get();

        foreach ($days as $key => $timetable) {
            foreach ($period as $key => $value) {
                if(utf8_decode(Fecha::parse($value)->locale('es')->isoFormat('dddd')) == strtolower(utf8_decode($timetable->day)))
                {
                    $newPeriod[] = Fecha::parse($value)->format('Y-m-d');
                }
            }
        }

        $timePermited = 30 / $days->count();

        foreach ($newPeriod->sort() as $key => $value) {
            $marking = Marking::HaveMarking($employee->cod_marking, $employee->cod_terminal)->whereDate('created_at',$value)->first();

            if($employee->type_employee == 1 && $employee->user)
            {
                $action = $employee->user->actionsEmp()->whereDate('created_at',Fecha::parse($value)->format('Y-m-d'))->first();
            }else{
                $action = Action::where('employee_id',$employee->id)->whereDate('created_at',$value)->first();
            }

            $permission = ($action) ? 'SI' : 'AUSENTE';


            if($marking)
            {
                $markings->push([
                    'date'          => Fecha::parse($value)->format('d/m/Y'),
                    'day'           => Fecha::parse($value)->locale('es')->isoFormat('dddd'),
                    'in'            => ($marking->check_in) ? Fecha::parse($marking->check_in)->format('H:i a') : 'Sin marcación',
                    'out'           => ($marking->check_out) ? Fecha::parse($marking->check_out)->format('H:i a') : 'Sin marcación',
                    'hours_worked'  => $marking->hours_worked ? str_replace('.',':',$marking->hours_worked) : null,
                    'extra_hours'   => $marking->extra_hours ? str_replace('.',':',$marking->extra_hours) : null,
                    'late_arrivals' => $marking->late_arrivals ? str_replace('.',':',$marking->late_arrivals) : null,
                    'permission'    => ($permission == 'SI') ? $permission : ''
                ]);
            }
            else
            {
                $markings->push([
                    'date'          => Fecha::parse($value)->format('d/m/Y'),
                    'day'           => Fecha::parse($value)->locale('es')->isoFormat('dddd'),
                    'in'            => 'Sin marcación',
                    'out'           => 'Sin marcación',
                    'hours_worked'  => '',
                    'extra_hours'   => '',
                    'late_arrivals' => '',
                    'permission'    => $permission
                ]);
            }
        }

        $filtered_worked    = $markings->where('hours_worked','!=',null);
        $filtered_worked    = $filtered_worked->pluck( str_replace('.',':','hours_worked') );
        $filtered_extra    = $markings->where('extra_hours','!=',null);
        $filtered_extra    = $filtered_extra->pluck( str_replace('.',':','extra_hours'));
        $filtered_minutes    = $markings->where('late_arrivals','!=',null);
        $filtered_minutes    = $filtered_minutes->pluck( str_replace('.',':','late_arrivals'));

        $timePermited = $timePermited * $markings->count();
        return collect([
            'markings' => $markings,
            'total_hours_worked'    => ($markings->isEmpty()) ? null : self::AddPlayTime($filtered_worked),
            'total_extra_hours'     => ($markings->isEmpty()) ? null : self::AddPlayTime($filtered_extra),
            'total_late_arrivals'   => ($markings->isEmpty()) ? null : self::AddPlayTimeArrivals($filtered_minutes, $timePermited)
            ]);
    }

    public static function AddPlayTime($times) {
        $minutes = 0;

        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;
        // returns the time already formatted
        return  sprintf('%02d:%02d', $hours, $minutes);
    }

    public static function AddPlayTimeArrivals($times, $timestable)
    {
        $minutes = (count($times) > 0) ? -$timestable : 0;

        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        

        // returns the time already formatted
        if(sprintf('%02d.%02d', $hours, $minutes) > 0){
            return  sprintf('%02d:%02d', $hours, $minutes);
        }else{
            return  '0:00';
        }
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
