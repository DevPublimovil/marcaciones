<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Marking;
use App\Employee;
use Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\Log;

class CalculateHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:hours {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para el calculo de horas trabajadas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * extraer marcaciones e insertarlas en tabla local
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->argument('date') ?? Fecha::now()->subday()->format('Y-m-d');

        $markings_local = Marking::whereDate('created_at',$date)->get();

        foreach ($markings_local as $key => $marking) {
            $employee = Employee::where('cod_marking', $marking->cod_marking)->where('cod_terminal',$marking->serialno)->first();
            if($employee){
                $date = new Fecha(Fecha::parse($employee->timestable->hour_out)->format('H:i:s'), 'America/El_Salvador');
                $datedos = new Fecha(Fecha::parse($employee->timestable->hour_in)->format('H:i:s'), 'America/El_Salvador');
                $diffHours = $date->diffInHours($datedos);
                $diffHours = $date->subHours(($diffHours / 2))->format('H:i');

                $hour = Fecha::parse($employee->timestable->hour_in)->format('H:i');
                if($marking->check_out == null)
                {
                    if($marking->check_in != null && Fecha::parse($marking->check_in)->format('H:i') > $diffHours)
                    {
                        $marking->update(['check_out' => $marking->check_in, 'check_in' => null]);
                    }
                }
                $realhour = Fecha::parse($marking->check_in)->format('H:i');
                if($marking->check_in != null && $realhour > $hour)
                {
                    $late_arrivals = $this->lateArrivals($hour, $realhour);
                    $marking->update([
                        'late_arrivals' => $late_arrivals
                    ]);
                }
                
                if($marking->check_out != null && $marking->check_in != null){
                    $hours_worked = $this->hoursWorked($employee, $marking);
                    $extra_hours = ($hours_worked > 8.00) ? $this->extraHours($hours_worked) : null;
                    $marking->update([
                        'hours_worked' => $hours_worked,
                        'extra_hours' => $extra_hours
                    ]);
                }
                
            }
           
        }
    }

    /**
     * calculo de horas trabajadas y horas extras
     * actualizar marcacion del empleado
     * 
     * @param arrays
     * 
     * @return void
     */

     public function extraHours($hours_worked)
     {
        $time = new \DateTime(str_replace('.',':',$hours_worked));
        $newtime = $time->sub(new \DateInterval('PT8H0M'));
        return $newtime->format('H.i');
     }

    
    public function hoursWorked($employee, $marking)
    {
        //verifico el tipo de usuario y determino el tiempo en minutos para restar
        $sub = ($employee->type_employee == 1) ? 'PT1H30M' : 'PT1H00M';

        $horaInicio = new \DateTime($marking->check_in);
        $horaTermino = new \DateTime($marking->check_out);

        $interval = $horaInicio->diff($horaTermino);
        
        $var = $interval->format('%H.%i');

        if($var > 6.00){
            $hour = new \DateTime($interval->format('%H:%i'));
            $calchour = $hour->sub(new \DateInterval($sub));
            return $calchour->format('H.i');
        }else{
            $hour = new \DateTime($interval->format('%H:%i'));
            return $hour->format('H.i');
        }
    }
    public function lateArrivals($hour, $realHour)
    {
        $horaInicio = new \DateTime($hour);
        $horaTermino = new \DateTime($realHour);

        $interval = $horaInicio->diff($horaTermino);
        $interval = new \DateTime($interval->format('%H:%i'));
        if($interval->format('H.i') > 05.00)
        {
            return 0.0;
        }else{
            return $interval->format('H.i');
        }
       
    }
}
