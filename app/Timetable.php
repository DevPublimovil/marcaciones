<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $guarded = [];
    

    public function days()
    {
        return $this->hasMany('App\DaysTable', 'timetable_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany('App\Employee', 'timetable_id', 'id');
    }
}
