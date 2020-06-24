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

    public function resourceCompany()
    {
        return $this->hasMany('App\CompanyResource', 'company_id', 'id');
    }

    public function actions()
    {
        return $this->hasManyThrough(
            'App\Action',
            'App\Employee',
            'company_id',
            'created_by',
            'id', 
            'id'
        );
    }
}
