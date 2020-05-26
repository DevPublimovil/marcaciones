<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalAction extends Model
{
    protected $guarded = [];
    
    public function Action()
    {
        return $this->belongsTo(Action::class);
    }

    public function typeactions()
    {
        return $this->belongsTo('App\ActionType', 'type_action_id', 'id');
    }
}
