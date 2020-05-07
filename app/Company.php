<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
    
    public function employees()
    {
        return $this->hasManyThrough('App\Employee','App\Employees_company');
    }
}
