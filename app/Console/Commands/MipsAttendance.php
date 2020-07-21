<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\Mips;
use \Carbon\Carbon as Fecha;
use App\Marking;
use App\MipsEmployee;

class MipsAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mips:markings {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para extraer marcaciones de MIPS';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $employees = Employee::whereNotNull('timetable_id')->get();

        foreach ($employees as $key => $employee) {
            $hoursdiff = Fecha::parse($employee->timestable->hour_in)->diffInHours($employee->timestable->hour_out);
            $datestart = Fecha::yesterday()->format('Y-m-d') . ' ' . $employee->timestable->hour_in;
            $datestart = Fecha::parse($datestart)->subHours(6);
            $dateend = Fecha::yesterday()->format('Y-m-d') . ' ' . $employee->timestable->hour_out;
            $dateend = Fecha::parse($datestart)->addHours($hoursdiff + 13);

            $employeemips = MipsEmployee::where('person_id','=',$employee->cod_marking)->first();
            if($employeemips){
                $markings = $employeemips->markings()->where('device_id', $employee->cod_terminal)->whereBetween('time',[$datestart->format('Y-m-d H:i:s'),$dateend->format('Y-m-d H:i:s')])->orderBy('time','ASC')->get();
                
                if($markings->count() > 0)
                {
                    $start = $markings->first();
                    $end = $markings->last();
                    $attend = Marking::firstOrCreate([
                        'serialno'      =>  $employee->cod_terminal,
                        'cod_marking'   =>  $employee->cod_marking,
                        'check_in'      =>  $start->time,
                        'check_out'     =>  $end->time,
                        'photo'         =>  'http://biometrico.grupopublimovil.com:9000/MIPS' . $end->img_path,
                        'created_at'    =>  Fecha::yesterday()->format('Y-m-d')
                    ]);

                    $attend->fill([
                        'temp_in'      =>  $start->temp,
                        'temp_out'     =>  $end->temp,
                    ]);

                    $attend->save();
                }
            }
        }
    }
}