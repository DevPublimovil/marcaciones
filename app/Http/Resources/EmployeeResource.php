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
        $start_week = Fecha::now()->startOfWeek()->format('Y-m-d');
        $end_week = Fecha::now()->endOfWeek()->format('Y-m-d');

        return [
                'id' => $this->id,
                'first_name' => $this->name_employee,
                'last_name' => $this->surname_employee,
                'cod' => $this->cod_marking,
                'position' => $this->position,
                'type' => $this->typeemployee->name_type_employee,
                'company' => $this->company->display_name,
                'departament' => $this->departament->display_name,
                'hoursworked' => $this->markings()->whereBetween('check_in',[$start_week, $end_week])->sum('hours_worked'),
                'extrahours' => $this->markings()->whereBetween('check_in',[$start_week, $end_week])->sum('extra_hours'),
                'latearrivals' => $this->markings()->whereBetween('check_in',[$start_week, $end_week])->sum('late_arrivals'),
            ];
    }
}
