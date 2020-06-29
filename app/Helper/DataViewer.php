<?php
/**
 * 
 */
namespace App\Helper;
use Validator;

trait DataViewer
{
    public function scopeSearchPaginateAndOrder($query, $firstTimestable)
    {
        $request = app()->make('request');
        $v = Validator::make($request->only([
            'column', 'direction','par_page','search_input'
        ]),[
            'column'            => 'required|alpha_dash|in:'.implode(',',self::$columns),
            'direction'         => 'required|in:asc,desc',
            'par_page'          => 'integer|min:1',
            'search_input'      => 'max:255'
        ]);

        if($v->fails())
        {
            dd($v->messages());
        }
        
        $timestable = $request->time ?? $firstTimestable ?? null;

        return $query->timestable($timestable)->orderBy(self::selectColumn($request->column), $request->direction)
                ->where(function($query) use($request){
                    if($request->search_input)
                    {
                        return $query->where('name_employee', 'LIKE', '%' . $request->search_input . '%')
                            ->orWhere('surname_employee', 'LIKE', '%' . $request->search_input . '%')
                            ->orWhere('employees.cod_marking', 'LIKE', '%' . $request->search_input . '%');
                    }
                })->paginate($request->per_page);
    }

    public function selectColumn($column)
    {
       switch ($column) {
           case 'apellidos':
               return 'surname_employee';
               break;
           case 'codigo':
               return 'cod_marking';
               break;
           case 'tipo':
               return 'type_employee';
               break;
           case 'compañia':
               return 'company_id';
               break;
           case 'departamento':
               return 'departament_id';
               break;
           case 'codigo':
               return 'cod_marking';
               break;
           
           default:
               return 'name_employee';
               break;
       }
    }
}