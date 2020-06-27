<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Webster_checkinout;
use App\Marking;
use Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\Log;

class MakeMarkings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:markings {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para extraer las marcaciones';

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

        $myday = $this->argument('date');

        $date_start = ($myday) ? Fecha::parse($myday)->subDay()->format('Y-m-d') . ' 06:00:00' : Fecha::now()->subDay()->format('Y-m-d') . ' 06:00:00';
        $date_end   = ($myday) ? $myday . ' 06:00:00' : Fecha::now()->format('Y-m-d') . ' 06:00:00';
        
        $markings = Webster_checkinout::whereBetween('checktime',[$date_start, $date_end])->orderBy('checktime','ASC')->get();
        
        foreach ($markings as $key => $marking) {
            $marcacion = Marking::where('cod_marking',$marking->userid)->where('serialno',$marking->serialno)->whereNotNull('check_in')->whereDate('created_at',Fecha::parse($date_start)->format('Y-m-d'))->first();
            if($marcacion)
            {
                //$marc = Marking::where('cod_marking',$marking->userid)->where('serialno',$marking->serialno)->whereNotNull('check_in')->whereDate('created_at',Fecha::parse($date_start)->format('Y-m-d'))->first();
                $marcacion->update([
                    'check_out' => $marking->checktime,
                ]);
            }
            else
            {
                Marking::create([
                    'cod_marking'       => $marking->userid,
                    'check_in'          => $marking->checktime,
                    'serialno'          => $marking->serialno,
                    'created_at'        => Fecha::parse($date_start)->format('Y-m-d'),
                ]);
            }

            if ($marking->userid == 87) {
                Log::info($marking->checktime . ' ' . $marking->cod_marking);
            }
        } 
    }
}
