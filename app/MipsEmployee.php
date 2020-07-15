<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MipsEmployee extends Model
{
    protected $connection = 'mips';

    protected $table = 'tb_employees_info';

    public function markings()
    {
        return $this->hasMany('App\Mips', 'vip_id', 'id');
    }
}
