<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon as Fecha;

class MarkingsEmployee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'date'      => Fecha::parse($this->created_at)->isoFormat('dddd') . ' - ' . Fecha::parse($this->created_at)->format('d/m/Y'),
            'photo'     => $this->photo,
            'in'        => ($this->check_in) ? Fecha::parse($this->check_in)->format('H:i a') : 'Sin marcación',
            'out'       => ($this->check_out) ? Fecha::parse($this->check_out)->format('H:i a') : 'Sin marcación',
            'minutes'   => $this->formatTime($this->late_arrivals)
        ];
    }

    public function formatTime($time)
    {
        $newtime = explode('.',$time);
        if($time > 1.00){
            return $newtime[0]. ' Horas y ' . $newtime[1] . ' minutos';
        }else if(!$time){
            return 0 . ' minutos';
        }else{
            return $newtime[1] . ' minutos';
        }
        
    }
}
