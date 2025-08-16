<?php

namespace App\Exceptions;

use Exception;

class AlreadyFriendsException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        parent::__construct(__('exceptions.alreadyFriendsException'), $this->code);
    }
}
