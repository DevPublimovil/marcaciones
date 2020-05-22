<?php

namespace App\Helper;
use Validator;

trait TraitName
{
    public function scopePaginateAndOrder($query)
    {

        return $query
                ->orderBy('name_employee','ASC')
                ->paginate(1);
    }
}
