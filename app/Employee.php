<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Marking;
use App\Helper\DataViewer;

class Employee extends Model
{
    use DataViewer;

    public static $columns = [
        'id','name_employee', 'surname_employee', 'cod_marking', 'cod_terminal', 'salary', 'position', 'type_employee','user_id','jefe_id','company_id','departament_id','created_at','updated_at'
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
