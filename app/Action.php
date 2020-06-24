<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{

    protected $guarded = [];

    public function personalaction()
    {
        return $this->hasMany(PersonalAction::class);
    }

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function scopeNoCheckGte($query){
        $query->where('check_gte',0);
    }

    public function scopeCheckGte($query){
        $query->where('check_gte',1);
    }

    public function scopeNoCheckRh($query){
        $query->where('check_rh',0);
    }

    public function scopeCheckRh($query){
        $query->where('check_rh',1);
    }

    public function scoperCheck($query)
    {
        $query->where('check_gte',1)->where('check_rh',1);
    }

    public function scopeOrNoCheck($query)
    {
        $query->where('check_gte',0)->orWhere('check_rh',0);
    }

    /* protected $casts = [
        'check_gte' => 'boolean',
        'check_rh' => 'boolean'
    ]; */
}
