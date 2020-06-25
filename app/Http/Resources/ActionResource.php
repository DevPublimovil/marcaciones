<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  \Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\Storage;

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
            'check_gte' => $this->check_gte ? true : false,
            'check_rh' => $this->check_rh ? true : false,
            'check_employee' => $this->check_employee ? true : false,
            'employee_id' => $this->employee_id,
            'attached' => ($this->attached) ? Storage::disk('public')->url($this->attached) : null,
            'diffHumans' => Fecha::parse($this->created_at)->diffForHumans(),
            'name_employee' => $this->user->name ?? $this->name ?? $this->name_employee . ' ' . $this->surname_employee,
        ];
    }
}
