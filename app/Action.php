<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{

    protected $guarded = [];

    public function personalaction()
    {
        return $this->hasMany(PersonalAction::class);
    }
}
