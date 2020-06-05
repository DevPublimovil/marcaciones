<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSession extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
