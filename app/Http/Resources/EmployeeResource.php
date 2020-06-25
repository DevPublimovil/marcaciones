<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon as Fecha;

class EmployeeResource extends JsonResource
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
                'first_name' => $this->name_employee,
                'last_name' => $this->surname_employee,
                'cod' => $this->cod_marking,
                'position' => $this->position,
                'timestable' => $this->timetable_id,
                'type' => $this->typeemployee->name_type_employee,
                'company' => $this->company->display_name,
                'departament' => $this->departament->display_name ?? null,
            ];
    }
}
