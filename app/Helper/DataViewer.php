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

    public function scopeSearchPaginateAndOrder($query, $columnsformatter, $searchcolumn)
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
            ->where(function($query) use ($request,$searchcolumn){
                if($request->search_input)
                {
                    if($searchcolumn == 'type_employee')
                    {
                        $query->typeemployee()->where($searchcolumn, 'LIKE', '%' . $request->search_input . '%');
                    }else if($searchcolumn == 'company_id')
                    {
                        $query->join('companies','companies.id','employees.company_id')->where('companies.display_name', 'LIKE', '%' . $request->search_input . '%');
                    }
                    else if($searchcolumn == 'departament_id')
                    {
                        $query->departament()->where($searchcolumn, 'LIKE', '%' . $request->search_input . '%');
                    }
                    else
                    {
                        $query->where($searchcolumn, 'LIKE', '%' . $request->search_input . '%');
                    }
                    /* if ($request->search_operator == 'in') 
                    {
                        $query->whereIn($request->search_column,explode(',', $request->search_input));
                    } */
                    /* else
                    {
                        $query->where($request->search_column, $this->operators[$request->search_operator],
                            $request->search_input);
                    } */
                }
            })
            ->paginate($request->per_page);
    }
}
