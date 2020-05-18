<?php
/**
 * 
 */
namespace App\Helper;
use Validator;
trait DataViewer
{
    protected $operators = [
        'equal' => '=',
        'not_equal' => '<>',
        'less_than' => '<',
        'grater_than' => '>',
        'less_than_or_equal_to' => '<=',
        'grater_than_or_equal_to' => '>='
    ];

    public function scopeSearchPaginateAndOrder($query)
    {
        $request = app()->make('request');
        $v = Validator::make($request->only([
            'column', 'direction','par_page','search_column','search_operator',
            'search_input'
        ]),[
            'column'            => 'required|alpha_dash|in:'.implode(',',self::$columns),
            'direction'         => 'required|in:asc,desc',
            'par_page'          => 'integer|min:1',
            'search_column'     => 'required|alpha_dash|in:'.implode(',',self::$columns),
            'search_operator'   => 'required|alpha_dash|in:'.implode(',',array_keys($this.operators)),
            'search_input'      => 'max:255'
        ]);

        if($v->fails())
        {
            dd($v->messages());
        }
        return $query
            ->orderBy($request->column, $request->direction)
            ->paginate($request->per_page);
    }
}
