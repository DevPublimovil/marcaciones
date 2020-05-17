<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    //
    protected $guarded = [];

    public function terminalrrhh()
    {
        return $this->hasMany(TerminalRrhh::class);
    }
}
