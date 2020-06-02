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

    public function actions()
    {
        return $this->hasMany('App\Action','created_by','id');
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

    public function searchDatatable($column, $request)
    {
        return $query->orderBy($column, $request->direction)
        ->where(function($query) use($request){
            if($request->search_input)
            {
                return $query->where('name_employee', 'LIKE', '%' . $request->search_input . '%')
                    ->orWhere('surname_employee', 'LIKE', '%' . $request->search_input . '%')
                    ->orWhere('employees.cod_marking', 'LIKE', '%' . $request->search_input . '%');
            }
        })->paginate($request->per_page);
    }
}
