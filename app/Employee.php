<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Marking;
use App\Helper\DataViewer;

class Employee extends Model
{

    use DataViewer;

    public static $columns = [
        'nombre', 'apellidos', 'codigo', /* 'cargo', 'tipo','compañia','departamento' */
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actions()
    {
        return $this->hasMany('App\Action', 'employee_id', 'id');
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

    public function getFullNameAttribute($value)
    {
        return $this->name_employee . ' ' . $this->surname_employee;
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeTimestable($query, $timestable)
    {
        return $query->where('timetable_id',$timestable);
    }
}
