<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mips extends Model
{
    protected $connection = 'mips';

    protected $table = 'tb_company';
}
