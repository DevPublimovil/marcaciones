<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Carbon\Carbon as Fecha;

class TimeTableResource extends JsonResource
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
            'id' => $this->id,
            'in' => Fecha::parse($this->hour_in)->format('H:i'),
            'out' =>  Fecha::parse($this->hour_out)->format('H:i')
        ];
    }
}
