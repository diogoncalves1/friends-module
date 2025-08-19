<?php

namespace App\Exceptions;

use Exception;

class FriendshipNotFoundException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        parent::__construct(__('exceptions.friendshipNotFoundException'), $this->code);
    }
}
