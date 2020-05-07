<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employees_company extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function departament()
    {
        return $this->belongsTo(Departament::class);
    }

    public function company()
    {
        return $this->belongsTo(company::class);
    }
}
