<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  \Carbon\Carbon as Fecha;

class ActionResource extends JsonResource
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
            'description' => $this->description,
            'created_at' => Fecha::parse($this->created_at)->format('d/m/Y'),
            'diffHumans' => Fecha::parse($this->created_at)->diffForHumans(),
        ];
    }
}
