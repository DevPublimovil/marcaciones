<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaysTable extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Timetable', 'timetable_id', 'id');
    }

    public $timestamps = false;
}
