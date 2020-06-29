<?php

namespace App\Helper;

class DynamicRecipient extends Recipient {

    public function __construct($email)
    {
        $this->email = $email;
    }

}