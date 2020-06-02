<?php
/**
 * 
 */
namespace App\Helper;
use Validator;
trait DataViewer
{
    /* protected $operators = [
        'equal' => '=',
        'not_equal' => '<>',
        'less_than' => '<',
        'grater_than' => '>',
        'less_than_or_equal_to' => '<=',
        'grater_than_or_equal_to' => '>=',
        'in' => 'IN',
        'like' => 'LIKE'
    ]; */

    public function scopeSearchPaginateAndOrder($query, $columnsformatter, $company)
    {
        $request = app()->make('request');
        $v = Validator::make($request->only([
            'column', 'direction','par_page','search_input'
        ]),[
            'column'            => 'required|alpha_dash|in:'.implode(',',self::$columns),
            'direction'         => 'required|in:asc,desc',
            'par_page'          => 'integer|min:1',
            //'search_operator'   => 'required|alpha_dash|in:'.implode(',',array_keys($this->operators)),
            'search_input'      => 'max:255'
        ]);

        if($v->fails())
        {
            dd($v->messages());
        }
        return $query
            ->orderBy($columnsformatter,$request->direction)
            ->where('jefe_id',45)
            ->where('company_id',$company)
            ->where('timetable_id',$request->time)
            ->where(function($query) use($request){
                if($request->search_input)
                {
                    return $query->where('name_employee', 'LIKE', '%' . $request->search_input . '%')
                        ->orWhere('surname_employee', 'LIKE', '%' . $request->search_input . '%')
                        ->orWhere('employees.cod_marking', 'LIKE', '%' . $request->search_input . '%');
                }
            })
            ->paginate($request->per_page);
    }
}