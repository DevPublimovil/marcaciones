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
    protected $signature = 'make:hours';

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
        $markings_local = Marking::whereDate('created_at',Fecha::now())->get();
        foreach ($markings_local as $key => $marking) {
            $employee = Employee::where('cod_marking', $marking->cod_marking)->first();
            
            $marking_update = Marking::find($marking->id);
            if($employee){
                $sub = ($employee->type_employee == 1) ? 90 : 60;
                
                if($marking->check_out != null){
                    $hours_worked = $this->hoursWorked($employee, $marking, $marking_update);
                }

                $timein = Fecha::create($marking->check_in);
                $time_correct = $timein->copy()->startOfDay()->addHours(8);
                $time_correct_night = $timein->copy()->startOfDay()->addHours(17);
                if($timein > $time_correct || $timein > $time_correct_night){
                    $late_arrivals = Fecha::parse($time_correct)->diffInMinutes($marking->check_in);
                    $marking_update->update(['late_arrivals' => ($late_arrivals > 0) ? $late_arrivals : null]);
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

    
    public function hoursWorked($employee, $marking, $marking_update)
    {
        $sub = ($employee->type_employee == 1) ? 90 : 60;
        $hours_worked = Fecha::parse($marking->check_out)->diffInMinutes($marking->check_in);
        $hours =  intval(($hours_worked - $sub) / 60);
        $explode = explode('.',(($hours_worked - $sub) / 60));
        $decimal = '0.'.$explode[1];
        $decimal = intval($decimal * 60);
        $hours_worked = (intval($hours) .'.'.$decimal);
        $marking_update->update([
            'hours_worked'  => $hours_worked,
            'extra_hours'   => ($hours_worked > 8.00) ? round($hours_worked - 8.00) : null,
        ]);
    }
}
