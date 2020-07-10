<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Employee;
use App\User;
use App\Picture;
use Illuminate\Support\Facades\Log;


class UpdateInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para actualizar la información del empleado';

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
        $users = User::all();

        foreach ($users as $user) {
            if($user->employee){
                $picture = Picture::where('picture_vip_id',$user->employee->cod_marking)->first();
                if($picture){
                    $user->update(['avatar' => 'http://192.168.50.152:9000/MIPS' . $picture->picture_url]);   
                }
            }
        }
    }
}
