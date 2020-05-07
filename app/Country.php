<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];

    public function employees()
    {
        return $this->hasManyThrough('App\Employee', 'App\Employees_company');
    }
}
 