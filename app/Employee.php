<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Marking;

class Employee extends Model
{ 

    public static $columns = [
        'nombre', 'apellidos', 'codigo', /* 'cargo', 'tipo','compañia','departamento' */
    ];

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
    

    public function markings()
    {
        return $this->hasMany('App\Marking', 'cod_marking', 'cod_marking');
    }

    public function typeemployee()
    {
        return $this->belongsTo('App\EmployeeType', 'type_employee', 'id');
    }

    public function jefe()
    {
        return $this->belongsTo('App\User', 'jefe_id', 'id');
    }

    public function timestable()
    {
        return $this->belongsTo('App\Timetable','timetable_id','id');
    }

    public function scopeSearchEmployee($query, $employee)
    {
        return $query->where('name_employee','LIKE','%'.$employee.'%')->orWhere('surname_employee','LIKE','%'.$employee.'%');
    }
}
