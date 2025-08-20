<?php

namespace App\Exceptions;

use Exception;

class UserBlockedException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        parent::__construct(__('exceptions.userBlockedException'), $this->code);
    }
}
