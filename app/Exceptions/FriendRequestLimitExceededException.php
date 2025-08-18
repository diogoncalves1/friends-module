<?php

namespace App\Exceptions;

use Exception;

class FriendRequestLimitExceededException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        parent::__construct(__('exceptions.friendRequestLimitExceededException'), $this->code);
    }
}
