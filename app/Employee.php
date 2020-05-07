<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function areaCountry()
    {
        return $this->belongsTo('App\Employees_company', 'employees_company_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function markings()
    {
        return $this->hasMany('App\Marking', 'cod_marking', 'cod_marking');
    }
}
