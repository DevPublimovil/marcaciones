<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\Picture;
use App\MipsEmployee;

class UpdateEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para actualizar avatar de empleado con sanipass';

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
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $employeemips = MipsEmployee::where('person_id','=',$employee->cod_marking)->first();
            if($employeemips){
                $picture = Picture::where('picture_vip_id',$employeemips->id)->first();
                if($picture){
                    $employee->update(['avatar' => 'http://sanipass.grupopublimovil.com:9000/MIPS' . $picture->picture_url]);
                }
            }
        }
    }
}
