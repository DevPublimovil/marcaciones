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
            'date'       => Fecha::parse($this->check_in)->isoFormat('dddd') . ' - ' . Fecha::parse($this->check_in)->format('d/m/Y'),
            'in'   => Fecha::parse($this->check_in)->format('H:i a'),
            'out'    => Fecha::parse($this->check_out)->format('H:i a'),
            'minutes'   => $this->late_arrivals
        ];
    }
}
