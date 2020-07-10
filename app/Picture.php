<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $connection = 'mips';

    protected $table = 'tb_employees_picture';
}
