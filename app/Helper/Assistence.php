<?php 
namespace App\Helper;
use App\Employee;
use \Carbon\Carbon as Fecha;
use App\Marking;
use App\Action;

class Assistence {
    
    public static function showAssists($request, $id)
    {
        $period = [];
        $markings = collect();
        $employee = Employee::find($id);
        if($request->start_date && $request->end_date)
        {
            $period = Fecha::parse($request->start_date)->toPeriod($request->end_date);
        }else{
            $start_date = Fecha::now()->startOfWeek()->format('Y-m-d');
            $end_date = Fecha::now()->endOfWeek()->format('Y-m-d');
            $period = Fecha::parse($start_date)->toPeriod($end_date);
        }

        foreach ($period as $key => $value) {
            $marking = Marking::where('serialno',$employee->cod_terminal)->where('cod_marking',$employee->cod_marking)->checktime($value)->first();
            $action = Action::where('created_by', $employee->id)->whereDate('created_At',Fecha::parse($value)->format('Y-m-d'))->first();
            $permission = ($action) ? 'SI' : 'AUSENTE';
            if($marking)
            {
                $late = ($marking->late_arrivals > 60) ? self::convertMinutes($marking->late_arrivals) : null;
                $markings[] = [
                    'date' => Fecha::parse($value)->format('d/m/Y'),
                    'day'   => Fecha::parse($value)->locale('es')->isoFormat('dddd'),
                    'in' => Fecha::parse($marking->check_in)->format('H:i a'),
                    'out'    => Fecha::parse($marking->check_out)->format('H:i a'),
                    'hours_worked' => $marking->hours_worked ?? null,
                    'extra_hours' => $marking->extra_hours ?? null,
                    'late_arrivals' => $late ?? $marking->late_arrivals,
                    'permission' => ($marking->check_in && $marking->check_out) ? '' : $permission
                ];
            }else{
                $markings[] = [
                    'date' => Fecha::parse($value)->format('d/m/Y'),
                    'day'   => Fecha::parse($value)->locale('es')->isoFormat('dddd'),
                    'in' => 'sin marcación',
                    'out'    => 'sin marcación',
                    'hours_worked' => null,
                    'extra_hours' => null,
                    'late_arrivals' => null,
                    'permission' => $permission
                ];
            }
        }

        $filtered_worked = [];
        $filtered_extra = [];
        $filtered_minutes = [];

        foreach ($markings as $key => $value) {
            if($value['hours_worked'] != null)
            {
                $filtered_worked[] = $value['hours_worked'];
            }

            if($value['extra_hours'] != null)
            {
                $filtered_extra[] = $value['extra_hours'];
            }

            if($value['late_arrivals'] != null)
            {
                $filtered_minutes[] = $value['late_arrivals'];
            }
        }

        return $assists = ([
            'markings' => $markings,
            'total_hours_worked' => self::AddPlayTime($filtered_worked),
            'total_extra_hours' => self::AddPlayTime($filtered_extra),
            'total_late_arrivals' => self::convertMinutes($filtered_minutes)
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

    public static function convertMinutes($times)
    {
        $total = 0;
        foreach ($times as  $time) {
            $total += $time;
        }

        $time = floor($total / 60);
        $total -= $time * 60;

        return  sprintf('%02d:%02d', $time, $total);
    }
}