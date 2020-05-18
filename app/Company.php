<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
