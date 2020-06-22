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

    public function scopeMyMarkings($query, $cod_marking, $cod_terminal)
    {
        return $query->where('serialno',$cod_terminal)->where('cod_marking',$cod_marking);
    }

    public function scopeHaveMarking($query, $cod, $terminal)
    {
        return $query->where('cod_marking',$cod)->where('serialno',$terminal);
    }
}
