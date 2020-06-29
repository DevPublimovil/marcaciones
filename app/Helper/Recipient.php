<?php

namespace App\Helper;

use Illuminate\Notifications\Notifiable;

abstract class Recipient{

    use Notifiable;

    protected $email;

}