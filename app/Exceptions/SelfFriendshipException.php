<?php

namespace App\Exceptions;

use Exception;

class SelfFriendshipException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        parent::__construct(__('exceptions.selfFriendshipException'), $this->code);
    }
}
