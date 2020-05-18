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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function departament()
    {
        return $this->belongsTo(Departament::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function markings()
    {
        return $this->hasMany('App\Marking', 'cod_marking', 'cod_marking');
    }

    public function typeemployee()
    {
        return $this->belongsTo('App\EmployeeType', 'type_employee', 'id');
    }
}
