<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marking extends Model
{
    protected $guarded = [];

    public function scopeChecktime($query, $date)
    {
        return $query->whereDate('check_in',$date)->orWhereDate('check_out',$date);
    }
}
