<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webster_checkinout extends Model
{
    protected $connection = 'markings';

    protected $table = 'webster_checkinout';
}
